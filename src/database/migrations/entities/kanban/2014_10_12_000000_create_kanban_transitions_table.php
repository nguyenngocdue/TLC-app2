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
        $schema->blueprintResolver(function ($table, $callback) {
            return new BlueprintExtended($table, $callback);
        });

        $schema->create('kanban_task_transitions', function (BlueprintExtended $table) {
            $table->id();
            $table->unsignedBigInteger('kanban_task_id')->nullable();
            $table->unsignedBigInteger('kanban_group_id')->nullable();

            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->unsignedBigInteger('excluded_seconds')->nullable();
            $table->unsignedBigInteger('elapsed_seconds')->nullable();

            $table->appendCommonFields();

            // $table->unique(['kanban_task_id', 'kanban_group_id']);
            $table->foreign('kanban_task_id')->references('id')->on('kanban_tasks')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kanban_group_id')->references('id')->on('kanban_task_groups')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("kanban_task_transitions");
    }
};
