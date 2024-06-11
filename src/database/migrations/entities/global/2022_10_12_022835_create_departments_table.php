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
        $schema->blueprintResolver(fn ($table, $callback) => new BlueprintExtended($table, $callback));

        $schema->create('departments', function (BlueprintExtended $table) {
            $table->id();
            $table->text('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('head_of_department')->nullable();
            $table->boolean('hide_in_org_chart')->nullable();
            $table->boolean('hide_in_survey')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('slug')->unique();

            $table->boolean('show_in_task_budget')->nullable();
            $table->orderable();
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
        Schema::dropIfExists('departments');
    }
};
