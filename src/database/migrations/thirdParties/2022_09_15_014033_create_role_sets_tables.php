<?php

use App\Utils\RoleRegistrar;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teams = config('permission.teams');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }
        if ($teams && empty($columnNames['team_foreign_key'] ?? null)) {
            throw new \Exception('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        Schema::create($tableNames['role_sets'], function (Blueprint $table) use ($teams, $columnNames) {
            $table->bigIncrements('id');
            if ($teams || config('permission.testing')) { // permission.testing is a fix for sqlite testing
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                $table->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index');
            }
            $table->string('name');       // For MySQL 8.0 use string('name', 125);
            $table->string('guard_name'); // For MySQL 8.0 use string('guard_name', 125);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            // $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            if ($teams || config('permission.testing')) {
                $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
            } else {
                $table->unique(['name', 'guard_name']);
            }
        });
        Schema::create($tableNames['model_has_role_sets'], function (Blueprint $table) use ($columnNames, $tableNames, $teams) {
            $table->unsignedBigInteger('role_set_id');
            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_role_sets_model_id_model_type_index');
            $table->foreign('role_set_id')
                ->references('id') // roles_set id
                ->on($tableNames['role_sets'])
                ->onDelete('cascade');
            if ($teams) {
                $table->unsignedBigInteger($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key'], 'model_has_role_sets_team_foreign_key_index');

                $table->primary(
                    [$columnNames['team_foreign_key'], 'role_set_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_role_sets_role_model_type_primary'
                );
            } else {
                $table->primary(
                    ['role_set_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_role_sets_role_model_type_primary'
                );
            }
        });
        Schema::create($tableNames['role_set_has_roles'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('role_set_id');
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')
                ->references('id') // role id
                ->on('roles')
                ->onDelete('cascade');
            $table->foreign('role_set_id')
                ->references('id') // role_set id
                ->on('role_sets')
                ->onDelete('cascade');
            $table->primary(['role_set_id', 'role_id'], 'role_set_has_roles_role_set_id_role_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_set_has_roles');
        Schema::dropIfExists('model_has_role_sets');
        Schema::dropIfExists('role_sets');
    }
};
