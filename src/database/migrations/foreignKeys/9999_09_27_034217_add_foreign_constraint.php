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
            // $table->foreign('featured_image')->references('id')->on('attachments')->onDelete('cascade');
            $table->foreign('category')->references('id')->on('user_categories')->onDelete('cascade');
            $table->foreign('position_prefix')->references('id')->on('user_position_pres')->onDelete('cascade');
            $table->foreign('position_1')->references('id')->on('user_position1s')->onDelete('cascade');
            $table->foreign('position_2')->references('id')->on('user_position2s')->onDelete('cascade');
            $table->foreign('position_3')->references('id')->on('user_position3s')->onDelete('cascade');
            $table->foreign('discipline')->references('id')->on('user_disciplines')->onDelete('cascade');
            $table->foreign('department')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('time_keeping_type')->references('id')->on('user_time_keep_types')->onDelete('cascade');
        });
        //************** POST MODULE **************/
        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('owner_id')->references('id')->on('users');
        });
        //************** PRODUCTION MODULE **************/
        Schema::table('prod_run_lines', function (Blueprint $table) {
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
        Schema::table('prod_routing_details', function (Blueprint $table) {
            $table->foreign('erp_routing_link_id')->references('id')->on('erp_routing_links')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('wir_description_id')->references('id')->on('wir_descriptions')->onDelete('cascade')->onUpdate('cascade');
        });
        //************** QAQC INSP MODULE **************/
        Schema::table('qaqc_insp_tmpls', function (Blueprint $table) {
            $table->foreign('prod_routing_id')->references('id')->on('prod_routings')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('qaqc_insp_tmpl_lines', function (Blueprint $table) {
            $table->foreign('qaqc_insp_tmpl_id')->references('id')->on('qaqc_insp_tmpls')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qaqc_insp_sheet_id')->references('id')->on('qaqc_insp_sheets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qaqc_insp_group_id')->references('id')->on('qaqc_insp_groups')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('qaqc_insp_chklsts', function (Blueprint $table) {
            $table->foreign('prod_order_id')->references('id')->on('prod_orders')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('qaqc_insp_chklst_lines', function (Blueprint $table) {
            $table->foreign('qaqc_insp_chklst_id')->references('id')->on('qaqc_insp_chklsts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qaqc_insp_sheet_id')->references('id')->on('qaqc_insp_sheets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qaqc_insp_group_id')->references('id')->on('qaqc_insp_groups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qaqc_insp_control_value_id')->references('id')->on('qaqc_insp_control_values')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('qaqc_insp_values', function (Blueprint $table) {
            $table->foreign('qaqc_insp_control_value_id')->references('id')->on('qaqc_insp_control_values')->onDelete('cascade')->onUpdate('cascade');
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
