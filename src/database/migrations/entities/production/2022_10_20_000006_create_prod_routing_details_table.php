<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('prod_routing_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prod_routing_id');
            $table->unsignedBigInteger('prod_routing_link_id');
            $table->unsignedBigInteger('erp_routing_link_id');
            $table->unsignedBigInteger('wir_description_id');
            $table->integer('priority')->nullable();
            $table->double('target_hours')->nullable();
            $table->double('target_man_hours')->nullable();

            // $table->primary(['prod_routing_id', 'prod_routing_link_id'], md5('prod_routing_id' . 'prod_routing_link_id'));
            $table->unique(['prod_routing_id', 'prod_routing_link_id'], md5('prod_routing_id' . 'prod_routing_link_id'));
            $table->unsignedBigInteger('owner_id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));;
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
