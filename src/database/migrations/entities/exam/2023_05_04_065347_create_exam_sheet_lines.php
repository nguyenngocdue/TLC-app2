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

        $schema->create('exam_sheet_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('exam_tmpl_id')->nullable();
            $table->unsignedBigInteger('exam_sheet_id')->nullable();
            $table->unsignedBigInteger('exam_question_id')->nullable();
            $table->unsignedBigInteger('question_type_id')->nullable();

            $table->string('sub_question_1_id')->nullable();
            $table->text('sub_question_1_value')->nullable();
            $table->string('sub_question_2_id')->nullable();
            $table->text('sub_question_2_value')->nullable();
            $table->text('response_ids')->nullable();
            $table->text('response_values')->nullable();

            $table->orderable();
            $table->appendCommonFields();

            $table->unique([
                'exam_tmpl_id',
                'exam_sheet_id',
                'exam_question_id',
                'sub_question_1_id',
                'sub_question_2_id',
            ], md5('exam_tmpl_id+exam_sheet_id+exam_question_id'));
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
