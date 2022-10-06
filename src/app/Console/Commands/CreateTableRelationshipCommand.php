<?php

namespace App\Console\Commands;

use App\Console\CommandsCreateTableRelationship\MigrationRelationShipCreator;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;

class CreateTableRelationshipCommand extends BaseCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'ndc:migration {name : The name of the migration}
        {--tables= : The tables=table1,table2}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration relationship file';

    /**
     * The migration creator instance.
     *
     * @var \App\Console\CommandsCreateTableRelationship\MigrationRelationShipCreator
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
    public function __construct(MigrationRelationShipCreator $creator, Composer $composer)
    {
        parent::__construct();
        $this->creator = $creator;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // It's possible for the developer to specify the tables to modify in this
        // schema operation. The developer may also specify if this table needs
        // to be freshly created so we can create the appropriate migrations.
        $name = Str::snake(trim($this->input->getArgument('name')));

        $tables = $this->input->getOption('tables');
        error_log($tables);
        $var = explode(',', $tables);
        $tableOne = $var[0];
        $tableTwo = $var[1];
        // If no table was given as an option but a create option is given then we
        // will use the "create" option as the table name. This allows the devs
        // to pass a table name into this option as a short-cut for creating.

        // Next, we will attempt to guess the table name if this the migration has
        // "create" in the name. This will allow us to provide a convenient way
        // of creating migrations that create new tables for the application.
        if (!$tableOne || !$tableTwo) {
        }

        // Now we are ready to write the migration out to disk. Once we've written
        // the migration out, we will dump-autoload for the entire framework to
        // make sure that the migrations are registered by the class loaders.
        $this->writeMigration($name, $tableOne, $tableTwo);
        $this->composer->dumpAutoloads();
    }

    /**
     * Write the migration file to disk.
     *
     * @param  string  $name
     * @param  string  $table
     * @param  bool  $create
     * @return string
     */
    protected function writeMigration($name, $tableOne, $tableTwo)
    {
        $file = $this->creator->create(
            $name,
            $this->getMigrationPath(),
            null,
            false,
            $tableOne,
            $tableTwo
        );

        $file = pathinfo($file, PATHINFO_FILENAME);
        $this->components->info(sprintf('Created migration [%s].', $file));
    }

    /**
     * Get migration path (either specified by '--path' option or default location).
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        return parent::getMigrationPath();
    }
}
