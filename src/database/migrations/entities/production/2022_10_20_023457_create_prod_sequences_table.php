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
        Schema::create('prod_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prod_order_id');
            $table->unsignedBigInteger('prod_routing_link_id');
            $table->unique(['prod_order_id', 'prod_routing_link_id']);
            $table->string('status')->nullable();
            $table->double('total_hours')->nullable();
            $table->double('total_man_hours')->nullable();
            $table->dateTime('expected_started_at')->nullable();
            $table->dateTime('expected_finished_at')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));;
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
