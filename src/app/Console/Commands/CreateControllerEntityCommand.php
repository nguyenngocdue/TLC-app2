<?php

namespace App\Console\Commands;

use App\Console\Commands\CreateControllerEntity\CreateControllerEntityCreator;
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
     * @var \App\Console\Commands\CreateControllerEntity\CreateControllerEntityCreator
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
        // $render = $this->input->getOption('render');
        $listName = [
            "ViewAllController",
            "EntityCRUDController",
            "ManageJsonController",
        ];
        $this->writeController($listName, $name);
        $this->composer->dumpAutoloads();
    }
    protected function writeController($listName, $name)
    {
        $this->createDirectory($name);
        $stubs = $this->getStub();
        foreach ($stubs as $key => $stub) {
            $file = $this->creator->create(
                $listName[$key],
                $name,
                $this->getControllerPath($name),
                $stub,
            );
            $file = pathinfo($file, PATHINFO_FILENAME);
            $this->components->info(sprintf('Created controller [%s].', $file));
        }
    }
    protected function getControllerPath($name)
    {
        return base_path("app/Http/Controllers/Entities/{$name}/");
    }
    protected function createDirectory($name)
    {
        $folder_name = self::getControllerPath($name);
        error_log("\nFolder to create controllers: $folder_name");
        if (!file_exists($folder_name)) File::makeDirectory($folder_name);
    }
    protected function getStub()
    {
        $sources = [
            '/ndc.controller.entityViewAll.stub',
            '/ndc.controller.entityCRUD.stub',
            '/ndc.controller.manage-json.stub',
        ];
        $creator = $this->creator;
        foreach ($sources as $stubName) {
            $customPath = $creator->getCustomPath() . $stubName;
            $stub = $creator->getFilesystem()->exists($customPath) ? $customPath : $this->stubPath() . $stubName;
            $result[] = $creator->getFilesystem()->get($stub);
        }
        return $result;
    }
}
