<?php

use App\BigThink\BlueprintExtended;
use Illuminate\Database\Migrations\Migration;
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
        $schema = DB::connection()->getSchemaBuilder();
        $schema->blueprintResolver(fn($table, $callback) => new BlueprintExtended($table, $callback));

        $schema->create('hr_tso_archive_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->unsignedBigInteger('tso_archive_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->unsignedBigInteger('user_discipline_id')->nullable();
            $table->unsignedBigInteger('user_cat_id')->nullable();
            $table->dateTime('date_time')->nullable();
            $table->unsignedInteger('min')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('sub_project_id')->nullable();
            $table->unsignedBigInteger('lod_id')->nullable();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->unsignedBigInteger('sub_task_id')->nullable();
            $table->unsignedBigInteger('work_mode_id')->nullable();
            $table->unsignedBigInteger("current_workplace_id")->nullable();
            $table->text('remark')->nullable();

            $table->hasStatus();
            $table->appendCommonFields();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_tso_archive_lines');
    }
};
