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

        $schema->create('eco_sheets', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable(); // title
            $table->text('description')->nullable(); //State of Change/Problem
            $table->string('revision_no')->nullable();
            $table->unsignedInteger('doc_id')->nullable();
            $table->string('slug')->nullable(); //->unique(); ??
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('priority_id')->nullable();
            $table->unsignedBigInteger('assignee_1')->nullable();
            $table->unsignedBigInteger('assignee_2')->nullable();
            $table->unsignedBigInteger('assignee_3')->nullable();
            $table->float('total_labor_cost')->nullable();
            $table->unsignedBigInteger('currency_1')->nullable();
            $table->float('total_add_cost')->nullable();
            $table->float('total_remove_cost')->nullable();
            $table->float('total_material_cost')->nullable();
            $table->unsignedBigInteger('currency_2')->nullable();
            $table->hasDueDate();
            $table->closable();
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
        Schema::dropIfExists('eco_sheets');
    }
};
