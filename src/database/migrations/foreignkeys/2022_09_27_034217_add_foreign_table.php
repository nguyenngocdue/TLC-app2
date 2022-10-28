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
        //************** USER MODULE **************/
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('workplace')->references('id')->on('workplaces')->onDelete('cascade');
            $table->foreign('user_type')->references('id')->on('user_types')->onDelete('cascade');
            $table->foreign('featured_image')->references('id')->on('media')->onDelete('cascade');
            $table->foreign('category')->references('id')->on('user_categories')->onDelete('cascade');
            $table->foreign('position_prefix')->references('id')->on('user_position_pres')->onDelete('cascade');
            $table->foreign('position_1')->references('id')->on('user_position1s')->onDelete('cascade');
            $table->foreign('position_2')->references('id')->on('user_position2s')->onDelete('cascade');
            $table->foreign('position_3')->references('id')->on('user_position3s')->onDelete('cascade');
            $table->foreign('discipline')->references('id')->on('user_disciplines')->onDelete('cascade');
            $table->foreign('department')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('time_keeping_type')->references('id')->on('user_time_keep_types')->onDelete('cascade');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('owner_id')->references('id')->on('users');
        });
        // This MUST be executed in the pivot migration file
        // Schema::table('prod_user_runs', function (Blueprint $table) {
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        //     $table->foreign('prod_line_id')->references('id')->on('prod_lines')->onDelete('cascade')->onUpdate('cascade');
        // });
        // Schema::table('prod_routing_details', function (Blueprint $table) {
        //     $table->foreign('routing_id')->references('id')->on('prod_routings')->onDelete('cascade')->onUpdate('cascade');
        //     $table->foreign('routing_link_id')->references('id')->on('prod_routing_links')->onDelete('cascade')->onUpdate('cascade');
        // });
        //************** PRODUCTION MODULE **************/
        Schema::table('prod_lines', function (Blueprint $table) {
            $table->foreign('prod_run_id')->references('id')->on('prod_runs');
        });
        Schema::table('prod_runs', function (Blueprint $table) {
            $table->foreign('prod_order_id')->references('id')->on('prod_orders');
            $table->foreign('prod_routing_link_id')->references('id')->on('prod_routing_links');
        });
        Schema::table('prod_orders', function (Blueprint $table) {
            $table->foreign('sub_project_id')->references('id')->on('sub_projects');
            $table->foreign('prod_routing_id')->references('id')->on('prod_routings');
        });
        Schema::table('prod_routing_links', function (Blueprint $table) {
            $table->foreign('prod_discipline_id')->references('id')->on('prod_disciplines');
        });
        Schema::table('sub_projects', function (Blueprint $table) {
            $table->foreign('sub_project_status_id')->references('id')->on('sub_project_statuses');
        });
        // Schema::table('mediables', function (Blueprint $table) {
        //     $table->foreign('media_id')->references('id')->on('media');
        //     $table->foreign('object_id')->references('id')->on('zunit_test_5s');
        //     $table->foreign('object_id')->references('id')->on('users');
        // });
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
