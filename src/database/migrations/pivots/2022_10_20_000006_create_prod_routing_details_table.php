<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->unsignedBigInteger('routing_id');
            $table->unsignedBigInteger('routing_link_id');
            $table->double('target_hours')->nullable();
            $table->double('target_man_hours')->nullable();
            $table->primary(['routing_id', 'routing_link_id']);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));;

            $table->foreign('routing_id')->references('id')->on('prod_routings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('routing_link_id')->references('id')->on('prod_routing_links')->onDelete('cascade')->onUpdate('cascade');
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
