<?php

namespace App\Console\Commands\Custom\Repository;

use App\Console\Commands\Custom\Helper\CustomCommandHelper;
use Exception;
use Illuminate\Console\Command;

class createRepository extends Command
{
    use CustomCommandHelper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repository:create {name} {--M|model} {--m|migration} {--f|factory} {--s|seeder} {--c|controller}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create repository';

    private $template_path = null;
    private $repository_path = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->template_path = base_path('app/Console/Commands/Custom/Repository/Templates');
        $this->repository_path = base_path('app/Repository');
    }


    private function createModel(string $model_namespace)
    {

        $createModel = $this->option('model');
        $params = [
            'name' => $model_namespace,
            '-f' => $this->option('factory'),
            '-m' => $this->option('migration'),
            '-s' => $this->option('seeder')
        ];

        if ($createModel)
            $this->call("make:model", $params);
        elseif ($params['-f'] || $params['-m'] || $params['-s'])
            throw new Exception("Factory/Migration/Seeder/Controller cannot be created without creating model via this command");

        $createController = $this->option('controller');
        if ($createController) {
            $this->call('make:controller', [
                'name' => "${model_namespace}Controller"
            ]);
        }
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arg = $this->argument("name") . "Repository";
        $model_namespace = str_replace("/", "\\", $this->argument("name"));
        $path = str_replace("\\", "/", $arg);
        $namespace = str_replace("/", "\\", $arg);

        $interface_namespace = $namespace . "Interface";

        $interface_file = file_get_contents($this->template_path . '/RepoInterfaceTemplate.php');

        $interface_file_replaceable = [
            "php" => "<?php",
            "NEW_NAMESPACE" => implode("\\", array_slice(explode("\\", $interface_namespace), 0, -1)),
            "NEW_INTERFACE" => @end(explode("\\", $interface_namespace))
        ];

        $interface_file = $this->strReplace($interface_file, $interface_file_replaceable);
        $interface_file_path = "$this->repository_path/Interfaces/${path}Interface.php";

        $class_file = file_get_contents($this->template_path . '/RepoClassTemplate.php');
        $class_file_replaceable = [
            "php" => "<?php",
            "NEW_NAMESPACE" => implode("\\", array_slice(explode("\\", $namespace), 0, -1)),
            "NEW_CLASS" => @end(explode("\\", $namespace)),
            "INTERFACE_NAMESPACE" => $interface_namespace,
            "INTERFACE_NAME" => @end(explode("\\", $interface_namespace)),
            "MODEL_NAMESPACE" => $model_namespace,
            "MODEL_NAME" => @end(explode("\\", $model_namespace))
        ];

        $class_file = $this->strReplace($class_file, $class_file_replaceable);
        $class_file_path = "$this->repository_path/Eloquent/$path.php";

        $this->createModel($model_namespace);

        $this->writeToFile($interface_file_path, $interface_file);
        $this->writeToFile($class_file_path, $class_file);
        $this->info('Repository created successfully.');
    }
}
