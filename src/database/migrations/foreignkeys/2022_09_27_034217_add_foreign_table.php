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
            $table->foreign('workplace')->references('id')->on('workplaces')->onDelete('cascade');
            $table->foreign('user_type')->references('id')->on('user_types')->onDelete('cascade');
            $table->foreign('featured_image')->references('id')->on('media')->onDelete('cascade');
            $table->foreign('category')->references('id')->on('user_categories')->onDelete('cascade');
            $table->foreign('position_prefix')->references('id')->on('user_position_pres')->onDelete('cascade');
            $table->foreign('position_1')->references('id')->on('user_positions_1')->onDelete('cascade');
            $table->foreign('position_2')->references('id')->on('user_positions_2')->onDelete('cascade');
            $table->foreign('position_3')->references('id')->on('user_positions_3')->onDelete('cascade');
            $table->foreign('discipline')->references('id')->on('user_disciplines')->onDelete('cascade');
            $table->foreign('department')->references('id')->on('departments')->onDelete('cascade');
        });
        Schema::table('media', function (Blueprint $table) {
            $table->foreign('owner_id')->references('id')->on('users');
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('owner_id')->references('id')->on('users');
        });
        Schema::table('user_disciplines', function (Blueprint $table) {
            $table->foreign('def_assignee')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('head_of_department')->references('id')->on('users')->onDelete('cascade');
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
