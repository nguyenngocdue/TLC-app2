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

        $schema->create('prod_runs', function (BlueprintExtended $table) {
            $table->id();
            $table->unsignedBigInteger('prod_sequence_id');
            $table->date('date')->nullable();
            $table->time('start')->nullable();
            $table->time('end')->nullable();
            $table->double('worker_number_input', 8, 2)->nullable();
            $table->unsignedInteger('worker_number_count')->nullable();
            $table->double('worker_number', 8, 2)->nullable();
            $table->double('total_hours', 8, 2)->nullable();
            $table->double('total_man_hours', 8, 2)->nullable();
            $table->double('production_output')->nullable();
            $table->string('remark')->nullable();

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
        Schema::dropIfExists('prod_runs');
    }
};
