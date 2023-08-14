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
        Schema::table('qaqc_insp_tmpls', function (Blueprint $table) {
            // $table->foreign('prod_routing_id')->references('id')->on('prod_routings');
        });
        Schema::table('qaqc_insp_tmpl_lines', function (Blueprint $table) {
            // $table->foreign('qaqc_insp_tmpl_id')->references('id')->on('qaqc_insp_tmpls')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreign('qaqc_insp_tmpl_sht_id')->references('id')->on('qaqc_insp_tmpl_shts');
            $table->foreign('qaqc_insp_tmpl_sht_id')->references('id')->on('qaqc_insp_tmpl_shts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('qaqc_insp_group_id')->references('id')->on('qaqc_insp_groups');
            $table->foreign('qaqc_insp_control_group_id')->references('id')->on('qaqc_insp_control_groups');
            $table->foreign('control_type_id')->references('id')->on('control_types');
        });
        Schema::table('qaqc_insp_chklsts', function (Blueprint $table) {
            $table->foreign('prod_order_id')->references('id')->on('prod_orders');
            $table->foreign('qaqc_insp_tmpl_id')->references('id')->on('qaqc_insp_tmpls');
        });
        Schema::table('qaqc_insp_chklst_lines', function (Blueprint $table) {
            $table->foreign('qaqc_insp_chklst_sht_id')->references('id')->on('qaqc_insp_chklst_shts')->cascadeOnUpdate()->cascadeOnDelete();
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
            $table->foreign('qaqc_insp_chklst_id')->references('id')->on('qaqc_insp_chklsts')->cascadeOnDelete()->cascadeOnUpdate();
        });
        Schema::table('qaqc_insp_chklst_shts', function (Blueprint $table) {
            $table->foreign('qaqc_insp_tmpl_sht_id')->references('id')->on('qaqc_insp_tmpl_shts')->cascadeOnDelete()->cascadeOnUpdate();
        });
        Schema::table('qaqc_mirs', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('sub_project_id')->references('id')->on('sub_projects');
            $table->foreign('prod_discipline_id')->references('id')->on('prod_disciplines');
            $table->foreign('priority_id')->references('id')->on('priorities');
            $table->foreign('assignee_1')->references('id')->on('users');
            $table->foreign('assignee_2')->references('id')->on('users');
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
            $table->foreign('user_team_id')->references('id')->on('user_team_ncrs');
            $table->foreign('priority_id')->references('id')->on('priorities');
            $table->foreign('assignee_1')->references('id')->on('users');
        });
        Schema::table('qaqc_cars', function (Blueprint $table) {
            $table->foreign('responsible_person')->references('id')->on('users');
        });
        Schema::table('qaqc_wir_lines', function (Blueprint $table) {
            $table->foreign('qaqc_wir_id')->references('id')->on('qaqc_wirs')->cascadeOnDelete();
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
