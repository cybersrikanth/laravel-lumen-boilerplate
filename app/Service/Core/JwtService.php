<?php

namespace App\Service\Core;

use App\Exceptions\JWT\ConstraintViolationException;
use App\Exceptions\JWT\InvalidExpirationTimeException;
use App\Exceptions\JWT\NoClaimsException;
use DateTimeImmutable;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;

class JwtService
{
    private $configuration;

    private $iss;
    private $expires_after_in_seconds;
    public function __construct()
    {
        $this->configuration = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText(config('jwt.secret'))
        );
        $this->expires_after_in_seconds = config('jwt.expires_after_in_seconds');
        $this->iss = config('jwt.iss');
    }

    private function setConstraints()
    {
        $this->configuration->setValidationConstraints(
            new SignedWith($this->configuration->signer(), $this->configuration->signingKey()),
            new ValidAt(new FrozenClock(new DateTimeImmutable()))
        );
    }

    public function getJwt(array $claims, int $expires_after_in_seconds = null): string
    {
        if (count($claims) === 0) throw new NoClaimsException();
        if ($expires_after_in_seconds === null)
            $expires_after_in_seconds = $this->expires_after_in_seconds;

        if ($expires_after_in_seconds <= 0) throw new InvalidExpirationTimeException('Expiration time should be atleast 1 second');
        $now = new DateTimeImmutable();

        $token = $this->configuration->builder()
            ->issuedBy($this->iss)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify("+ $expires_after_in_seconds seconds"));

        foreach ($claims as $key => $value) {
            $token = $token->withClaim($key, $value);
        }

        return $token->getToken($this->configuration->signer(), $this->configuration->signingKey())->toString();
    }

    public function validate(string $jwt): array
    {
        try {
            $token = $this->configuration->parser()->parse($jwt);
            $this->setConstraints();

            $this->configuration->validator()->assert($token, ...$this->configuration->validationConstraints());
            return $token->claims()->all();
        } catch (RequiredConstraintsViolated $e) {
            $error_message = $e->getMessage();
            $errors = [];
            preg_match('/- (.*)/', $error_message, $errors);
            throw new ConstraintViolationException(end($errors));
        }
    }
}
