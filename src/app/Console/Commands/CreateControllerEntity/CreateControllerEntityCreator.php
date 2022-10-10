<?php

namespace App\Console\CreateControllerEntity;

use App\Models\Permission;
use App\Models\Role;
use Closure;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CreateControllerEntityCreator
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The custom app stubs directory.
     *
     * @var string
     */
    protected $customStubPath;

    /**
     * The registered post create hooks.
     *
     * @var array
     */
    protected $postCreate = [];

    /**
     * Create a new migration creator instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  string  $customStubPath
     * @return void
     */
    public function __construct(Filesystem $files, $customStubPath)
    {
        $this->files = $files;
        $this->customStubPath = $customStubPath;
    }

    /**
     * Create a new migration at the given path.
     *
     * @param  string  $name
     * @param  string  $path
     * @param  bool  $create
     * @return string
     *
     * @throws \Exception
     */
    public function create($nameClass, $name, $path, $stub, $render)
    {

        // First we will get the stub file for the migration, which serves as a type
        // of template for the migration. Once we have those we will populate the
        // various place-holders, save the file, and run the post create event.

        $path = $this->getPath($nameClass, $path);

        $this->files->ensureDirectoryExists(dirname($path));

        $this->files->put(
            $path,
            $this->populateStub($stub, $name, $render)
        );
        $this->firePostCreateHooks($nameClass, $path);
        // Next, we will fire any hooks that are supposed to fire after a migration is
        // created. Once that is done we'll be ready to return the full path to the
        // migration file so it can be used however it's needed by the developer.
        return $path;
    }

    /**
     * Populate the place-holders in the controller stub.
     *
     * @param  string  $stub
     * @param  string|null  $name
     * @return string
     */
    protected function populateStub($stub, $name, $render)
    {
        // Here we will replace the table place-holders with the table specified by
        // the developer, which is useful for quickly creating a tables creation
        // or update migration from the console instead of typing it manually.
        if (!is_null($name)) {
            $nameClass = Str::ucfirst($name);
            $nameClassSingular = Str::singular($nameClass);
            $name = Str::singular(strtolower($name));
            $stub = str_replace(
                ['{{nameClass}}', '{{nameModel}}', '{{nameClassSingular}}'],
                [$nameClass, $name, $nameClassSingular],
                $stub
            );
            if ($render && !Permission::where('name', "read-$name")->first()) {
                $result = "read-$name|create-$name|edit-$name|edit-others-$name|delete-$name|delete-others-$name";
                $permissions = explode('|', $result);
                foreach ($permissions as $permission) {
                    try {
                        Permission::create(['name' => $permission]);
                    } catch (\Throwable $th) {
                        return $stub;
                    }
                }
                $nameRole = Str::upper($nameClass);
                Role::create(['name' => "READ-DATA-$nameRole"])->givePermissionTo("read-$name");
                Role::create(['name' => "READ-WRITE-DATA-$nameRole"])->givePermissionTo(["read-$name", "edit-$name", "create-$name", "edit-others-$name"]);
                Role::create(['name' => "ADMIN-DATA-$nameRole"])->givePermissionTo(["read-$name", "edit-$name", "create-$name", "edit-others-$name", "delete_$name", "delete_others_$name"]);
            }
        };
        return $stub;
    }

    /**
     * Get the class name of a controller name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getClassName($name)
    {
        return Str::studly($name);
    }

    /**
     * Get the full path to the controller.
     *
     * @param  string  $name
     * @param  string  $path
     * @return string
     */
    protected function getPath($name, $path)
    {
        return $path . $name . '.php';
    }

    /**
     * Fire the registered post create hooks.
     *
     * @param  string|null  $table
     * @param  string  $path
     * @return void
     */
    protected function firePostCreateHooks($table, $path)
    {
        foreach ($this->postCreate as $callback) {
            $callback($table, $path);
        }
    }

    /**
     * Register a post controller create hook.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function afterCreate(Closure $callback)
    {
        $this->postCreate[] = $callback;
    }

    /**
     * Get the path to the stubs.
     *
     * @return string
     */
    public function stubPath()
    {
        return __DIR__ . '/stubs';
    }

    /**
     * Get the filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->files;
    }
    public function getCustomPath()
    {
        return $this->customStubPath;
    }
}
