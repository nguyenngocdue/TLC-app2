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
        //************** SYSTEM MODULE **************/
        Schema::table('terms', function (Blueprint $table) {
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
        });
        Schema::table('many_to_many', function (Blueprint $table) {
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('category')->references('id')->on('fields')->onDelete('cascade');
        });
        Schema::table('attachments', function (Blueprint $table) {
            $table->foreign('category')->references('id')->on('fields')->onDelete('cascade');
        });
        //************** GLOBAL MODULE **************/
        Schema::table('sub_projects', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
        Schema::table('priorities', function (Blueprint $table) {
            $table->foreign('field_id')->references('id')->on('fields');
        });
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
        //************** HR MODULE **************/
        Schema::table('hr_overtime_requests', function (Blueprint $table) {
            $table->foreign('workplace_id')->references('id')->on('workplaces');
        });
        Schema::table('public_holidays', function (Blueprint $table) {
            $table->foreign('workplace_id')->references('id')->on('workplaces')->onDelete('cascade');
        });
        Schema::table('hr_overtime_request_lines', function (Blueprint $table) {
            $table->foreign('work_mode_id')->references('id')->on('work_modes');
            $table->foreign('sub_project_id')->references('id')->on('sub_projects');
            $table->foreign('user_id')->references('id')->on('users');
        });
        //************** PRODUCTION MODULE **************/
        Schema::table('prod_runs', function (Blueprint $table) {
            $table->foreign('prod_sequence_id')->references('id')->on('prod_sequences');
        });
        Schema::table('prod_disciplines', function (Blueprint $table) {
            $table->foreign('def_assignee')->references('id')->on('users');
        });
        Schema::table('prod_discipline_1s', function (Blueprint $table) {
            $table->foreign('def_assignee')->references('id')->on('users');
            $table->foreign('prod_discipline_id')->references('id')->on('prod_disciplines');
        });
        Schema::table('prod_discipline_2s', function (Blueprint $table) {
            $table->foreign('prod_discipline_1_id')->references('id')->on('prod_discipline_1s');
        });
        Schema::table('prod_sequences', function (Blueprint $table) {
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
        Schema::table('prod_routing_details', function (Blueprint $table) {
            $table->foreign('erp_routing_link_id')->references('id')->on('erp_routing_links')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('wir_description_id')->references('id')->on('wir_descriptions')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('wir_descriptions', function (Blueprint $table) {
            $table->foreign('prod_discipline_id')->references('id')->on('prod_disciplines');
            $table->foreign('def_assignee')->references('id')->on('users');
        });
        Schema::table('erp_routing_links', function (Blueprint $table) {
            $table->foreign('prod_discipline_id')->references('id')->on('prod_disciplines');
        });
        Schema::table('pj_modules', function (Blueprint $table) {
            $table->foreign('pj_unit_id')->references('id')->on('pj_units');
            $table->foreign('pj_shipment_id')->references('id')->on('pj_shipments');
        });
        Schema::table('pj_pods', function (Blueprint $table) {
            $table->foreign('pj_module_id')->references('id')->on('pj_modules');
        });
        Schema::table('pj_shipments', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('sub_project_id')->references('id')->on('sub_projects');
        });
        //************** HSE MODULE **************/
        Schema::table('hse_incident_reports', function (Blueprint $table) {
            $table->foreign('work_area_id')->references('id')->on('work_areas');
            $table->foreign('injured_person')->references('id')->on('users');
            $table->foreign('line_manager')->references('id')->on('users');
            $table->foreign('report_person')->references('id')->on('users');
        });
        Schema::table('hse_corrective_actions', function (Blueprint $table) {
            $table->foreign('priority_id')->references('id')->on('priorities');
            $table->foreign('work_area_id')->references('id')->on('work_areas');
            $table->foreign('assignee')->references('id')->on('users');
        });

        //************** QAQC INSP MODULE **************/
        Schema::table('qaqc_insp_tmpls', function (Blueprint $table) {
            $table->foreign('prod_routing_id')->references('id')->on('prod_routings')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('qaqc_insp_tmpl_lines', function (Blueprint $table) {
            // $table->foreign('qaqc_insp_tmpl_id')->references('id')->on('qaqc_insp_tmpls')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qaqc_insp_tmpl_sht_id')->references('id')->on('qaqc_insp_tmpl_shts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qaqc_insp_group_id')->references('id')->on('qaqc_insp_groups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qaqc_insp_control_group_id')->references('id')->on('qaqc_insp_control_groups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('control_type_id')->references('id')->on('control_types')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('qaqc_insp_chklsts', function (Blueprint $table) {
            $table->foreign('prod_order_id')->references('id')->on('prod_orders')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qaqc_insp_tmpl_id')->references('id')->on('qaqc_insp_tmpls');
        });
        Schema::table('qaqc_insp_chklst_lines', function (Blueprint $table) {
            // $table->foreign('qaqc_insp_chklst_id')->references('id')->on('qaqc_insp_chklsts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qaqc_insp_chklst_run_id')->references('id')->on('qaqc_insp_chklst_runs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qaqc_insp_group_id')->references('id')->on('qaqc_insp_groups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qaqc_insp_control_group_id')->references('id')->on('qaqc_insp_control_groups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qaqc_insp_control_value_id')->references('id')->on('qaqc_insp_control_values')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('control_type_id')->references('id')->on('control_types')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('qaqc_insp_values', function (Blueprint $table) {
            $table->foreign('qaqc_insp_control_value_id')->references('id')->on('qaqc_insp_control_values')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('qaqc_insp_control_values', function (Blueprint $table) {
            $table->foreign('qaqc_insp_control_group_id')->references('id')->on('qaqc_insp_control_groups')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('qaqc_insp_tmpl_shts', function (Blueprint $table) {
            $table->foreign('qaqc_insp_tmpl_id')->references('id')->on('qaqc_insp_tmpls')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('qaqc_insp_chklst_shts', function (Blueprint $table) {
            $table->foreign('qaqc_insp_chklst_id')->references('id')->on('qaqc_insp_chklsts')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('qaqc_insp_chklst_runs', function (Blueprint $table) {
            $table->foreign('qaqc_insp_chklst_sht_id')->references('id')->on('qaqc_insp_chklst_shts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('qaqc_mirs', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('sub_project_id')->references('id')->on('sub_projects');
            $table->foreign('prod_discipline_id')->references('id')->on('prod_disciplines');
            $table->foreign('priority_id')->references('id')->on('priorities');
            $table->foreign('assignee_1')->references('id')->on('users');
            $table->foreign('inspected_by')->references('id')->on('users');
        });
        Schema::table('qaqc_wirs', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('sub_project_id')->references('id')->on('sub_projects');
            $table->foreign('prod_routing_id')->references('id')->on('prod_routings');
            $table->foreign('prod_order_id')->references('id')->on('prod_orders');
            $table->foreign('prod_discipline_id')->references('id')->on('prod_disciplines');
            $table->foreign('wir_description_id')->references('id')->on('wir_descriptions');
            $table->foreign('priority_id')->references('id')->on('priorities');
            $table->foreign('assignee_1')->references('id')->on('users');
        });
        Schema::table('qaqc_ncrs', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('sub_project_id')->references('id')->on('sub_projects');
            $table->foreign('prod_routing_id')->references('id')->on('prod_routings');
            $table->foreign('prod_order_id')->references('id')->on('prod_orders');
            $table->foreign('prod_discipline_id')->references('id')->on('prod_disciplines');
            $table->foreign('prod_discipline_1_id')->references('id')->on('prod_discipline_1s');
            $table->foreign('prod_discipline_2_id')->references('id')->on('prod_discipline_2s');
            $table->foreign('user_team_id')->references('id')->on('user_teams');
            $table->foreign('priority_id')->references('id')->on('priorities');
            $table->foreign('assignee_1')->references('id')->on('users');
        });
        Schema::table('qaqc_cars', function (Blueprint $table) {
            $table->foreign('responsible_person')->references('id')->on('users');
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
