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
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('workplace')->references('id')->on('workplaces');
            $table->foreign('user_type')->references('id')->on('user_types');
            $table->foreign('company')->references('id')->on('user_companies');
            $table->foreign('category')->references('id')->on('user_categories');
            $table->foreign('position_prefix')->references('id')->on('user_position_pres');
            $table->foreign('position_1')->references('id')->on('user_position1s');
            $table->foreign('position_2')->references('id')->on('user_position2s');
            $table->foreign('position_3')->references('id')->on('user_position3s');
            $table->foreign('discipline')->references('id')->on('user_disciplines');
            $table->foreign('department')->references('id')->on('departments');
            $table->foreign('time_keeping_type')->references('id')->on('user_time_keep_types');
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
