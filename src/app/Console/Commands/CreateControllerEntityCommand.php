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
            $listName = ["Manage{$name}PropController", "Manage{$name}RelationshipController", "Manage{$name}TablePropController"];
            $this->writeController($listName, $name, $render);
            $this->composer->dumpAutoloads();
        } else {
            $name = Str::plural($name);
            $listName = ["{$name}RenderController", "{$name}EditController", "{$name}CreateController"];
            $this->writeController($listName, $name, $render);
            $this->composer->dumpAutoloads();
        }
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
            File::makeDirectory("app/Http/Controllers/Manage/{$name}");
        } else {
            $name = Str::plural($name);
            File::makeDirectory("app/Http/Controllers/Render/{$name}");
        }
    }
    protected function getControllerPath($name, $render)
    {
        if (!$render) {
            return base_path("app/Http/Controllers/Manage/{$name}/");
        }
        $name = Str::plural($name);
        return base_path("app/Http/Controllers/Render/{$name}/");
        //[$pathManage, $pathRender] = [base_path('app/Http/Controller/Manage'), base_path('app/Http/Controller/Render')];
    }
    protected function getStub($render)
    {
        if (!$render) {
            $stub1 = $this->creator->getFilesystem()->exists($customPath = $this->creator->getCustomPath() . '/controller.manageprop.stub')
                ? $customPath
                : $this->stubPath() . '/ndc.controller.manageprop.stub';
            $stub2 = $this->creator->getFilesystem()->exists($customPath = $this->creator->getCustomPath() . '/controller.managerelationship.stub')
                ? $customPath
                : $this->stubPath() . '/ndc.controller.managerelationship.stub';
            $stub3 = $this->creator->getFilesystem()->exists($customPath = $this->creator->getCustomPath() . '/controller.managetableprop.stub')
                ? $customPath
                : $this->stubPath() . '/ndc.controller.managetableprop.stub';
            $stubs = [$stub1, $stub2, $stub3];
            $result = [];
            foreach ($stubs as $stub) {
                array_push($result, $this->creator->getFilesystem()->get($stub));
            }
            return $result;
        } else {
            $stub1 = $this->creator->getFilesystem()->exists($customPath = $this->creator->getCustomPath() . '/controller.render.stub')
                ? $customPath
                : $this->stubPath() . '/ndc.controller.render.stub';
            $stub2 = $this->creator->getFilesystem()->exists($customPath = $this->creator->getCustomPath() . '/controller.edit.stub')
                ? $customPath
                : $this->stubPath() . '/ndc.controller.edit.stub';
            $stub3 = $this->creator->getFilesystem()->exists($customPath = $this->creator->getCustomPath() . '/controller.create.stub')
                ? $customPath
                : $this->stubPath() . '/ndc.controller.create.stub';
            $stubs = [$stub1, $stub2, $stub3];
            $result = [];
            foreach ($stubs as $stub) {
                array_push($result, $this->creator->getFilesystem()->get($stub));
            }
            return $result;
        }
    }
}
