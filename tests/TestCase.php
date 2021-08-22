<?php

use ApiDoc\DocGenerator;
use App\Models\Users\Session;
use App\Models\Users\User;
use Faker\Factory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    protected $request = [
        "method" => "GET",
        "uri" => "",
        "headers" => ["Accept" => "application/json", "Content-Type" => "application/json"],
        "body" => []
    ];

    protected $faker;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->setUp();
        (new DocGenerator())->init(); // this method clears previously generated docs.
        $this->faker = Factory::create();

        Artisan::call('migrate:fresh');
    }

    public function setUp(): void
    {
        parent::setUp();
        // DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        // DB::rollBack();
        // parent::tearDown();
    }

    protected function setRequestHeader($key, $value)
    {
        $headers = $this->request["headers"];
        $headers[$key] = $value;
        $this->request['headers'] = $headers;
    }
    protected function useAuth(User $user, string $role = ''): object
    {
        $session = Session::factory()->create(['user_Id' => $user->id]);
        $user->setCurrentSession($session);
        $this->actingAs($user);
        $this->setRequestHeader("Authorization", "Bearer {{" . $role . " token}}");
        return $this;
    }

    protected function fire()
    {
        return $this->call($this->request["method"], $this->request["uri"], $this->request["body"], $this->request["headers"]);
    }
}
