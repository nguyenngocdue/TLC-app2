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
        Schema::table('prod_runs', function (Blueprint $table) {
            $table->foreign('prod_sequence_id')->references('id')->on('prod_sequences')->cascadeOnDelete();
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
