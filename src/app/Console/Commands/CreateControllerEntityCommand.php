<?php

namespace App\Console\Commands;

use App\Console\CreateControllerEntity\CreateControllerEntityCreator;
use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateControllerEntityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ndc:controller {name : The name of the controller manage}
    {--render : The true create controller render}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller file';

    /**
     * The migration creator instance.
     *
     * @var \App\Console\CreateControllerEntity\CreateControllerEntityCreator
     */
    protected $creator;

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * Create a new migration install command instance.
     *
     * @return void
     */
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function __construct(CreateControllerEntityCreator $creator, Composer $composer)
    {
        parent::__construct();
        $this->creator = $creator;
        $this->composer = $composer;
    }
    public function handle()
    {
        $name = Str::ucfirst(Str::snake(trim($this->input->getArgument('name'))));
        $render = $this->input->getOption('render');
        if (!$render) {
            $listName = [
                "PropController",
                "RelationshipController",
                "StatusController",
            ];
        } else {
            $name = Str::plural($name);
            $listName = [
                "{$name}ViewAllController",
                "{$name}EditController",
                "{$name}CreateController"
            ];
        }
        $this->writeController($listName, $name, $render);
        $this->composer->dumpAutoloads();
    }
    protected function writeController($listName, $name, $render)
    {
        $this->createDirectory($name, $render);
        $stubs = $this->getStub($render);
        foreach ($stubs as $key => $stub) {
            $file = $this->creator->create(
                $listName[$key],
                $name,
                $this->getControllerPath($name, $render),
                $stub,
                $render
            );
            $file = pathinfo($file, PATHINFO_FILENAME);
            $this->components->info(sprintf('Created controller [%s].', $file));
        }
    }
    protected function createDirectory($name, $render)
    {
        if (!$render) {
            $folder_name = "app/Http/Controllers/Manage/{$name}";
        } else {
            $name = Str::plural($name);
            $folder_name = "app/Http/Controllers/Render/{$name}";
        }
        error_log("\nFolder to create controllers: $folder_name");
        if (!file_exists($folder_name)) File::makeDirectory($folder_name);
    }
    protected function getControllerPath($name, $render)
    {
        if (!$render) {
            return base_path("app/Http/Controllers/Manage/{$name}/");
        }
        $name = Str::plural($name);
        return base_path("app/Http/Controllers/Render/{$name}/");
    }
    protected function getStub($render)
    {
        if (!$render) {
            $sources = [
                '/ndc.controller.manageprop.stub',
                '/ndc.controller.managerelationship.stub',
                '/ndc.controller.managestatus.stub',
            ];
        } else {
            $sources = [
                '/ndc.controller.render.stub',
                '/ndc.controller.edit.stub',
                '/ndc.controller.create.stub',
            ];
        }
        $creator = $this->creator;
        foreach ($sources as $stubname) {
            $customPath = $creator->getCustomPath() . $stubname;
            $stub = $creator->getFilesystem()->exists($customPath) ? $customPath : $this->stubPath() . $stubname;
            $result[] = $creator->getFilesystem()->get($stub);
        }
        return $result;
    }
}
