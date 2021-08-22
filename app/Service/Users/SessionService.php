<?php

namespace App\Service\Users;

use App\Exceptions\Session\InvalidOrExpiredSessionException;
use App\Models\Users\Session;
use App\Models\Users\User;
use App\Repository\Interfaces\Users\SessionRepositoryInterface;
use App\Service\Core\JwtService;
use Carbon\Carbon;

class SessionService
{

    private $session_repository;
    private $jwt_service;

    // In hours
    private $expire_after_inactive_for;

    // In minutes
    private $refresh_session_frequency;

    public function __construct(
        SessionRepositoryInterface $session_repository,
        JwtService $jwt_service
    ) {
        $this->session_repository = $session_repository;
        $this->jwt_service = $jwt_service;
        $this->expire_after_inactive_for = config('session.expire_after_inactive_for');
        $this->refresh_session_frequency = 5;
    }

    private function validateSessionExpiration(Session $session): void
    {
        $now = Carbon::now();
        if ($session->updated_at->addHours($this->expire_after_inactive_for)->lt($now)) {
            $this->session_repository->deleteById($session->id);
            throw new InvalidOrExpiredSessionException();
        }
        if ($session->updated_at->addMinutes($this->refresh_session_frequency)->lt($now)) {
            $this->session_repository->touch($session->id);
        }
    }

    public function clearAllInactiveSessions(): int
    {
        $clear_before = Carbon::now()->subHours($this->expire_after_inactive_for);

        return $this->session_repository->deleteMany([
            ['updated_at', '<', $clear_before]
        ]);
    }

    public function createSessionAndGetJwt(int $user_id, string $ip_address, string $user_agent = 'Unknown'): string
    {
        $session = $this->session_repository->create([
            'user_id' => $user_id,
            'user_agent' => $user_agent,
            'ip_address' => $ip_address
        ]);

        $claims = [
            'session_id' => $session->id
        ];

        return $this->jwt_service->getJwt($claims);
    }

    public function getUser(int $session_id): User
    {
        $session = $this->session_repository->findById($session_id, ['*'], ['user']);
        if (!$session) throw new InvalidOrExpiredSessionException();
        
        $this->validateSessionExpiration($session);
        
        $user = $session->user;
        $user->setCurrentSession($session);

        return $user;
    }
}
