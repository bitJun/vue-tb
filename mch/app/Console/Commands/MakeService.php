<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;

class MakeService extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        if(!$name)
        {
            $this->error("Not enough arguments (missing: \"name\"). ");
            return false;
        }

        $serviceName = 'Services/'.$name;
        $name = $this->qualifyClass($serviceName);

        $path = $this->getPath($name);

        if ($this->alreadyExists($name)) {
            $this->error('Service already exists!');

            return false;
        }

        $this->makeDirectory($path);

        $stub = $this->getStub();
        $buildClass = $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);

        $this->files->put($path, $buildClass);

        $this->info('Service created successfully!');
    }

    public function getStub()
    {
        // TODO: Implement getStub() method.
        return "<?php\n\nnamespace DummyNamespace;\n\nclass DummyClass\n{\n//\n}\n";
    }
}
