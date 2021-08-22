<?php

namespace App\Console\Commands\Session;

use App\Service\Users\SessionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ClearInactiveSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:clearInactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears all inactive sessions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(
        SessionService $session_service
    )
    {
        $cleared_sessions = $session_service->clearAllInactiveSessions();

        $message = "$cleared_sessions inactive sessions have been cleared";

        $this->info($message);
        Log::info($message);
    }
}
