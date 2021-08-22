<?php

namespace App\Console\Commands\Custom\Service;

use App\Console\Commands\Custom\Helper\CustomCommandHelper;
use Illuminate\Support\Str;

use Illuminate\Console\Command;

class createService extends Command
{
    use CustomCommandHelper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:create {name} {dependsOn?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Service';

    private $template_path = null;
    private $service_path = null;
    private $repo_interface_namespace = null;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->template_path = base_path('app/Console/Commands/Custom/Service/Templates');
        $this->service_path = base_path('app/Service');
        $this->repo_interface_namespace = "App\\Repository\\Interfaces";
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arg = $this->argument('name');
        $depends_on = $this->argument('dependsOn');
        $service_namespace = str_replace("/", "\\", $arg) . "Service";
        $path_name = $this->service_path . '/' . str_replace("\\", "/", $service_namespace);

        $service_file = file_get_contents($this->template_path . '/ServiceTemplate.php');
        $service_file_replaceable = [
            "php" => "<?php",
            "SERVICE_NAMESPACE" => implode("\\", array_slice(explode("\\", $service_namespace), 0, -1)),
            "NEW_CLASS" => @end(explode("\\", $service_namespace)),
            // "INTERFACE_NAMESPACE" => $interface_namespace,
            // "INTERFACE" => @end(explode("\\", $interface_namespace)),
            // "REPOSITORY_NAME" => Str::snake(@end(explode("/", "${arg}Repository")))
        ];
        foreach ($depends_on as $i => $dependancy) {
            $namespace = str_replace("/", "\\", $dependancy) . "Repository";
            $name = @end(explode("\\", $namespace));
            $repo_var = Str::snake($name);
            $param_sep = (++$i === count($depends_on))?"":",";
            $parsed_dependancy = [
                '// USE' => "use $this->repo_interface_namespace\\${namespace}Interface;\n// USE",
                '// CLASS_VAR_DECL' => "private \$$repo_var;\n\t// CLASS_VAR_DECL",
                '// CONSTRUCT_PARAM' => "${name}Interface \$$repo_var$param_sep\n\t\t// CONSTRUCT_PARAM",
                '// CONSTRUCT_BODY' => '$this->' . $repo_var . " = \$$repo_var;\n\t\t// CONSTRUCT_BODY"
            ];
            $service_file = $this->strReplace($service_file, $parsed_dependancy);
        }
        $service_file = $this->strReplace($service_file, $service_file_replaceable);
        $service_file_replaceable = [
            '/\n.*\/\/ USE/' => "",
            '/\n.*\/\/ CLASS_VAR_DECL/' => "",
            '/\n.*\/\/ CONSTRUCT_PARAM/' => "",
            '/\n.*\/\/ CONSTRUCT_BODY/' => ''
        ];

        $service_file = $this->strReplace($service_file, $service_file_replaceable, true);

        $this->writeToFile("$path_name.php", $service_file);
        $this->info("Service created successfully.");
    }
}
