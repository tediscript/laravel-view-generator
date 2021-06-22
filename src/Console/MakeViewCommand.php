<?php

namespace Tediscript\ViewGenerator\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class MakeViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view
                                {path : The path to generated view file}
                                {name=Item : The name of the model class} 
                                {--t|type=plain : Manually specify the view stub file to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view blade file';

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
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = null;

        if ($type = $this->option('type')) {
            $stub = "/stubs/{$type}.stub";
        }

        $stub = $stub ?? '/stubs/plain.stub';

        return $this->resolveStubPath($stub);
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = resource_path(trim($stub, '/')))
                        ? $customPath
                        : __DIR__.$stub;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        return $this->createView();
    }

    protected function createView()
    {
        $path = $this->argument('path');
        $name = $this->argument('name');

        $this->line('Create view...');
        $pluralName = Str::plural($name);
        $resourceName = Str::lower($pluralName);
        $modelName = Str::camel($name);
        $modelCollectionName = Str::plural($modelName);

        $viewsPath = resource_path('views');
        File::ensureDirectoryExists($viewsPath);

        $stubPath = $this->getStub();
        $template = file_get_contents($stubPath);
        $template = Str::replace('{{ name }}', $name, $template);
        $template = Str::replace('{{ pluralName }}', $pluralName, $template);
        $template = Str::replace('{{ resourceName }}', $resourceName, $template);
        $template = Str::replace('{{ modelName }}', $modelName, $template);
        $template = Str::replace('{{ modelCollectionName }}', $modelCollectionName, $template);

        $path = trim($path, '/');
        $viewPath = "${viewsPath}/${path}.blade.php";
        File::ensureDirectoryExists(dirname($viewPath));
        file_put_contents($viewPath, $template);
    }

}
