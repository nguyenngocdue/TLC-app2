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

        $schema->create('mec_books', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable(); // title
            $table->text('description')->nullable(); //State of Change/Problem
            $table->unsignedInteger('doc_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('sub_project_id')->nullable();

            $table->unsignedBigInteger('priority_id')->nullable();
            $table->hasDueDate();

            $table->unsignedBigInteger('from_department_id')->nullable();
            $table->unsignedBigInteger('to_department_id')->nullable();

            $table->unsignedBigInteger('assignee_1')->nullable();
            $table->unsignedBigInteger('assignee_2')->nullable();
            $table->unsignedBigInteger('assignee_3')->nullable();

            $table->closable();
            $table->orderable();
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
        Schema::dropIfExists('mec_books');
    }
};
