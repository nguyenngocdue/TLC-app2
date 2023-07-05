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
        //************** HSE **************/
        Schema::table('hse_insp_chklst_shts', function (Blueprint $table) {
            $table->foreign('hse_insp_tmpl_sht_id')->references('id')->on('hse_insp_tmpl_shts')->cascadeOnDelete()->cascadeOnUpdate();
        });
        Schema::table('hse_insp_tmpl_lines', function (Blueprint $table) {
            $table->foreign('hse_insp_tmpl_sht_id')->references('id')->on('hse_insp_tmpl_shts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('hse_insp_group_id')->references('id')->on('hse_insp_groups');
            $table->foreign('hse_insp_control_group_id')->references('id')->on('hse_insp_control_groups');
            $table->foreign('control_type_id')->references('id')->on('control_types');
        });
        Schema::table('hse_insp_chklst_lines', function (Blueprint $table) {
            $table->foreign('hse_insp_chklst_sht_id')->references('id')->on('hse_insp_chklst_shts'); // version 2
            $table->foreign('hse_insp_group_id')->references('id')->on('hse_insp_groups');
            $table->foreign('hse_insp_control_group_id')->references('id')->on('hse_insp_control_groups');
            $table->foreign('hse_insp_control_value_id')->references('id')->on('hse_insp_control_values');
            $table->foreign('control_type_id')->references('id')->on('control_types');
            $table->foreign('owner_id')->references('id')->on('users');
        });
        Schema::table('hse_insp_values', function (Blueprint $table) {
            $table->foreign('hse_insp_control_value_id')->references('id')->on('hse_insp_control_values');
        });
        Schema::table('hse_insp_control_values', function (Blueprint $table) {
            $table->foreign('hse_insp_control_group_id')->references('id')->on('hse_insp_control_groups');
        });
        //************** ECO **************/
        Schema::table('eco_effectiveness_lines', function (Blueprint $table) {
            $table->foreign('eco_sheet_id')->references('id')->on('eco_sheets');
        });
        Schema::table('eco_labor_impacts', function (Blueprint $table) {
            $table->foreign('eco_sheet_id')->references('id')->on('eco_sheets');
        });
        Schema::table('eco_material_impact_adds', function (Blueprint $table) {
            $table->foreign('eco_sheet_id')->references('id')->on('eco_sheets');
        });
        Schema::table('eco_material_impact_removes', function (Blueprint $table) {
            $table->foreign('eco_sheet_id')->references('id')->on('eco_sheets');
        });
        Schema::table('eco_taken_actions', function (Blueprint $table) {
            $table->foreign('eco_sheet_id')->references('id')->on('eco_sheets');
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
