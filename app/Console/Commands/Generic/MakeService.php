<?php

namespace App\Console\Commands\Generic;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {namespace} {serviceName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service that is used in the system';

    /**
     * The name of the dummy class in the stub file to be replaced with the actual provided one.
     *
     * @var string
     */
    protected $dummyClass = 'DummyClass';

    /**
     * The name of the dummy namespace in the stub file to be replaced with the actual provided one.
     *
     * @var string
     */
    protected $dummyNamespace = 'DummyNamespace';

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
    public function handle()
    {
        $serviceName = $this->argument('serviceName');
        $namespace = $this->argument('namespace');

        if ($serviceName && $namespace) {
            $replacedString = str_replace(array($this->dummyClass, $this->dummyNamespace), array($serviceName, $namespace), \File::get(base_path('app/Console/Commands/Generic/stubs/service.stub')));
            $newFileName = base_path('app/Services/' . $namespace . '/' . $serviceName . '.php');
            $newFilePath = base_path('app/Services/' . $namespace . '/');

            if (!file_exists($newFileName)) {
                if (!file_exists($newFilePath)) {
                    \File::makeDirectory($newFilePath);
                }

                $success = \File::put(base_path('app/Services/' . $namespace . '/' . $serviceName . '.php'), $replacedString);
                if ($success) {
                    $this->info('Service created successfully.');
                }
            } else {
                $this->error('Service already exists with this name !');
            }
        } else {
            $this->error('Please provide a valid service name and namespace for it !');
        }
    }
}
