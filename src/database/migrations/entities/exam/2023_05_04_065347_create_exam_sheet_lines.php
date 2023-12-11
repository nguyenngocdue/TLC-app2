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

        $schema->create('exam_sheet_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('exam_tmpl_id')->nullable();
            $table->unsignedBigInteger('exam_sheet_id')->nullable();
            $table->unsignedBigInteger('exam_question_id')->nullable();
            $table->unsignedBigInteger('question_type_id')->nullable();

            $table->unsignedBigInteger('sub_question_1')->nullable();
            $table->unsignedBigInteger('sub_question_2')->nullable();
            $table->unsignedBigInteger('response_ids')->nullable();
            $table->text('response_values')->nullable();

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
        Schema::dropIfExists('exam_sheet_lines');
    }
};
