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

        $schema->create('prod_routing_details', function (BlueprintExtended $table) {
            $table->id();
            $table->unsignedBigInteger('prod_routing_id');
            $table->unsignedBigInteger('prod_routing_link_id')->nullable();
            $table->unsignedBigInteger('erp_routing_link_id')->nullable();
            $table->unsignedBigInteger('wir_description_id')->nullable();
            $table->integer('priority')->nullable(); //<<TO be removed

            $table->double('target_hours')->nullable();
            $table->double('target_man_power')->nullable();
            $table->double('target_man_hours')->nullable();
            $table->double('target_min_uom')->nullable();
            $table->double('avg_actual_hours')->nullable();

            $table->orderable();
            $table->appendCommonFields();

            $table->unique(['prod_routing_id', 'prod_routing_link_id'], md5('prod_routing_id' . 'prod_routing_link_id'));
            $table->foreign('prod_routing_id')->references('id')->on('prod_routings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('prod_routing_link_id')->references('id')->on('prod_routing_links')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod_routing_details');
    }
};
