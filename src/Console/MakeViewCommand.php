<?php

namespace Tediscript\ViewGenerator\Console;

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
                                {name : The name of generated view blade file}
                                {--m|model=Item : Generate a view for the given model} 
                                {--l|layout=plain : Manually specify the stub view layout to use}';

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

        if ($type = $this->option('layout')) {
            $type = Str::replace('.', '/', $type);
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
                        : __DIR__ . '/../../' . $stub;
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
        $name = $this->argument('name');
        $model = $this->option('model');

        $this->line('Create view...');
        $pluralModel = Str::plural($model);
        $resourceName = Str::lower($pluralModel);
        $instanceModel = Str::camel($model);
        $instanceCollectionModel = Str::plural($instanceModel);

        $viewsPath = resource_path('views');
        File::ensureDirectoryExists($viewsPath);

        $stubPath = $this->getStub();
        $template = file_get_contents($stubPath);
        $template = Str::replace('{{ model }}', $model, $template);
        $template = Str::replace('{{ pluralModel }}', $pluralModel, $template);
        $template = Str::replace('{{ resourceName }}', $resourceName, $template);
        $template = Str::replace('{{ instanceModel }}', $instanceModel, $template);
        $template = Str::replace('{{ instanceCollectionModel }}', $instanceCollectionModel, $template);

        $path = Str::replace('.', '/', $name);
        $path = trim($path, '/');
        $viewPath = "${viewsPath}/${path}.blade.php";
        File::ensureDirectoryExists(dirname($viewPath));
        file_put_contents($viewPath, $template);
    }

}
