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

        $schema->create('exam_tmpl_questions', function (BlueprintExtended $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('exam_tmpl_id')->nullable();
            $table->unsignedBigInteger('exam_tmpl_group_id')->nullable();
            $table->unsignedBigInteger('question_type_id')->nullable();
            $table->boolean('render_as_rows')->nullable();
            $table->text('static_answer')->nullable();
            $table->unsignedBigInteger('dynamic_answer')->nullable();

            $table->hasStatus();
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
        Schema::dropIfExists('exam_tmpl_questions');
    }
};
