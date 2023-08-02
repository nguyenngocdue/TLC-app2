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

        $schema->create('prod_sequences', function (BlueprintExtended $table) {
            $table->id();
            $table->unsignedBigInteger('prod_order_id');
            $table->unsignedBigInteger('prod_routing_link_id');
            $table->unsignedBigInteger('sub_project_id')->nullable();
            $table->unsignedBigInteger('prod_routing_id')->nullable();

            $table->unique(['prod_order_id', 'prod_routing_link_id']);
            $table->integer('priority')->nullable();
            $table->double('total_hours')->nullable();
            $table->double('worker_number')->nullable();
            $table->double('total_man_hours')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->dateTime('expected_start_at')->nullable();
            $table->dateTime('expected_finish_at')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->float('total_uom')->nullable();
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
        Schema::dropIfExists('prod_sequences');
    }
};
