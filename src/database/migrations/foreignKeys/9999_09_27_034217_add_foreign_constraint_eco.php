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
