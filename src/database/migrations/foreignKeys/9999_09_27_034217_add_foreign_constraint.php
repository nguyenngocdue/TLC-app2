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
            $table->foreign('field_id')->references('id')->on('fields');
        });
        Schema::table('many_to_many', function (Blueprint $table) {
            $table->foreign('field_id')->references('id')->on('fields');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('category')->references('id')->on('fields');
        });
        Schema::table('attachments', function (Blueprint $table) {
            $table->foreign('category')->references('id')->on('fields');
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
            $table->foreign('workplace')->references('id')->on('workplaces');
            $table->foreign('user_type')->references('id')->on('user_types');
            $table->foreign('category')->references('id')->on('user_categories');
            $table->foreign('position_prefix')->references('id')->on('user_position_pres');
            $table->foreign('position_1')->references('id')->on('user_position1s');
            $table->foreign('position_2')->references('id')->on('user_position2s');
            $table->foreign('position_3')->references('id')->on('user_position3s');
            $table->foreign('discipline')->references('id')->on('user_disciplines');
            $table->foreign('department')->references('id')->on('departments');
            $table->foreign('time_keeping_type')->references('id')->on('user_time_keep_types');
        });
        //************** POST MODULE **************/
        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
        //************** HR MODULE **************/
        Schema::table('hr_overtime_requests', function (Blueprint $table) {
            $table->foreign('workplace_id')->references('id')->on('workplaces');
        });
        Schema::table('public_holidays', function (Blueprint $table) {
            $table->foreign('workplace_id')->references('id')->on('workplaces');
        });
        Schema::table('hr_overtime_request_lines', function (Blueprint $table) {
            $table->foreign('work_mode_id')->references('id')->on('work_modes');
            $table->foreign('sub_project_id')->references('id')->on('sub_projects');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('hr_overtime_request_id')->references('id')->on('hr_overtime_requests')->onDelete('cascade');
        });
        Schema::table('user_team_ots', function (Blueprint $table) {
            $table->foreign('owner_id')->references('id')->on('users');
        });
        // Schema::table('ts_tasks', function (Blueprint $table) {
        //     $table->foreign('owner_id')->references('id')->on('users');
        // });
        // Schema::table('ts_subtasks', function (Blueprint $table) {
        //     $table->foreign('owner_id')->references('id')->on('users');
        //     $table->foreign('ts_task_id')->references('id')->on('ts_tasks');
        // });
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
            $table->foreign('erp_routing_link_id')->references('id')->on('erp_routing_links');
            $table->foreign('wir_description_id')->references('id')->on('wir_descriptions');
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
            $table->foreign('pj_shipment_id')->references('id')->on('pj_shipments');
        });
        Schema::table('pj_staircases', function (Blueprint $table) {
            $table->foreign('pj_shipment_id')->references('id')->on('pj_shipments');
        });
        Schema::table('pj_shipments', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('sub_project_id')->references('id')->on('sub_projects');
        });
        //************** HSE MODULE **************/
        Schema::table('hse_incident_reports', function (Blueprint $table) {
            $table->foreign('work_area_id')->references('id')->on('work_areas');
            $table->foreign('injured_person')->references('id')->on('users');
            $table->foreign('assignee_1')->references('id')->on('users');
            $table->foreign('owner_id')->references('id')->on('users');
        });
        Schema::table('hse_corrective_actions', function (Blueprint $table) {
            $table->foreign('priority_id')->references('id')->on('priorities');
            $table->foreign('work_area_id')->references('id')->on('work_areas');
            $table->foreign('assignee_1')->references('id')->on('users');
        });

        //************** QAQC INSP MODULE **************/
        Schema::table('qaqc_insp_tmpls', function (Blueprint $table) {
            $table->foreign('prod_routing_id')->references('id')->on('prod_routings');
        });
        Schema::table('qaqc_insp_tmpl_lines', function (Blueprint $table) {
            // $table->foreign('qaqc_insp_tmpl_id')->references('id')->on('qaqc_insp_tmpls')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qaqc_insp_tmpl_sht_id')->references('id')->on('qaqc_insp_tmpl_shts');
            $table->foreign('qaqc_insp_group_id')->references('id')->on('qaqc_insp_groups');
            $table->foreign('qaqc_insp_control_group_id')->references('id')->on('qaqc_insp_control_groups');
            $table->foreign('control_type_id')->references('id')->on('control_types');
        });
        Schema::table('qaqc_insp_chklsts', function (Blueprint $table) {
            $table->foreign('prod_order_id')->references('id')->on('prod_orders');
            $table->foreign('qaqc_insp_tmpl_id')->references('id')->on('qaqc_insp_tmpls');
        });
        // Schema::table('qaqc_insp_chklst_run_lines', function (Blueprint $table) {
        //     // $table->foreign('qaqc_insp_chklst_id')->references('id')->on('qaqc_insp_chklsts')->onDelete('cascade')->onUpdate('cascade');
        //     $table->foreign('qaqc_insp_chklst_run_id')->references('id')->on('qaqc_insp_chklst_runs');
        //     $table->foreign('qaqc_insp_group_id')->references('id')->on('qaqc_insp_groups');
        //     $table->foreign('qaqc_insp_control_group_id')->references('id')->on('qaqc_insp_control_groups');
        //     $table->foreign('qaqc_insp_control_value_id')->references('id')->on('qaqc_insp_control_values');
        //     $table->foreign('control_type_id')->references('id')->on('control_types');

        //     $table->foreign('owner_id')->references('id')->on('users');
        //     $table->foreign('inspector_id')->references('id')->on('users');
        // });  //version 1 
        Schema::table('qaqc_insp_chklst_lines', function (Blueprint $table) {
            // $table->foreign('qaqc_insp_chklst_id')->references('id')->on('qaqc_insp_chklsts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qaqc_insp_chklst_sht_id')->references('id')->on('qaqc_insp_chklst_shts'); // version 2
            // $table->foreign('qaqc_insp_chklst_run_id')->references('id')->on('qaqc_insp_chklst_runs'); // version 1
            $table->foreign('qaqc_insp_group_id')->references('id')->on('qaqc_insp_groups');
            $table->foreign('qaqc_insp_control_group_id')->references('id')->on('qaqc_insp_control_groups');
            $table->foreign('qaqc_insp_control_value_id')->references('id')->on('qaqc_insp_control_values');
            $table->foreign('control_type_id')->references('id')->on('control_types');

            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('inspector_id')->references('id')->on('users');
        });
        Schema::table('qaqc_insp_values', function (Blueprint $table) {
            $table->foreign('qaqc_insp_control_value_id')->references('id')->on('qaqc_insp_control_values');
        });
        Schema::table('qaqc_insp_control_values', function (Blueprint $table) {
            $table->foreign('qaqc_insp_control_group_id')->references('id')->on('qaqc_insp_control_groups');
        });
        Schema::table('qaqc_insp_tmpl_shts', function (Blueprint $table) {
            $table->foreign('qaqc_insp_tmpl_id')->references('id')->on('qaqc_insp_tmpls');
        });
        Schema::table('qaqc_insp_chklst_shts', function (Blueprint $table) {
            $table->foreign('qaqc_insp_chklst_id')->references('id')->on('qaqc_insp_chklsts');
        });
        Schema::table('qaqc_insp_chklst_sht_sigs', function (Blueprint $table) {
            $table->foreign('qaqc_insp_chklst_sht_id')->references('id')->on('qaqc_insp_chklst_shts');
        });
        // Schema::table('qaqc_insp_chklst_runs', function (Blueprint $table) {
        //     $table->foreign('qaqc_insp_chklst_sht_id')->references('id')->on('qaqc_insp_chklst_shts');
        //     $table->foreign('owner_id')->references('id')->on('users');
        // }); // version 1 
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

        //************** ESG **************/
        Schema::table('ghg_metric_type_1s', function (Blueprint $table) {
            $table->foreign('ghg_metric_type_id')->references('id')->on('ghg_metric_types');
        });
        Schema::table('ghg_metric_type_2s', function (Blueprint $table) {
            $table->foreign('ghg_metric_type_1_id')->references('id')->on('ghg_metric_type_1s');
        });
        Schema::table('ghg_lines', function (Blueprint $table) {
            $table->foreign('ghg_metric_type_1_id')->references('id')->on('ghg_metric_type_1s');
            $table->foreign('ghg_metric_type_2_id')->references('id')->on('ghg_metric_type_2s');
            $table->foreign('ghg_metric_type_id')->references('id')->on('ghg_metric_types');
            $table->foreign('ghg_sheet_id')->references('id')->on('ghg_sheets');
            $table->foreign('unit')->references('id')->on('terms');
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
