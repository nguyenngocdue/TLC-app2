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
            $table->timestamps();
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
