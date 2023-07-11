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
         // Account 
         Schema::table('act_currency_xr_lines', function (Blueprint $table) {
            $table->foreign('currency_xr_id')->references('id')->on('act_currency_xrs');
        });
        Schema::table('act_currency_pairs', function (Blueprint $table) {
            $table->foreign('base_currency_id')->references('id')->on('act_currencies');
            $table->foreign('counter_currency_id')->references('id')->on('act_currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
