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

        $schema->create('esg_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->unsignedBigInteger('type_parent_1');
            $table->unsignedBigInteger('type_parent_2');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('unit');
            $table->float('factor');
            $table->float('m01')->nullable();
            $table->float('m02')->nullable();
            $table->float('m03')->nullable();
            $table->float('m04')->nullable();
            $table->float('m05')->nullable();
            $table->float('m06')->nullable();
            $table->float('m07')->nullable();
            $table->float('m08')->nullable();
            $table->float('m09')->nullable();
            $table->float('m10')->nullable();
            $table->float('m11')->nullable();
            $table->float('m12')->nullable();
            $table->unsignedInteger('year');
            $table->float('ytd');
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('esg_lines');
    }
};
