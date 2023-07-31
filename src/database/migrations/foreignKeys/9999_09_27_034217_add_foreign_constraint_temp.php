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
         // Qaqc 
         Schema::table('qaqc_wir_lines', function (Blueprint $table) {
            $table->foreign('qaqc_wir_id')->references('id')->on('qaqc_wirs');
        });
        //Hr
        Schema::table('hr_onboarding_lines', function (Blueprint $table) {
            $table->foreign('hr_onboarding_id')->references('id')->on('hr_onboardings');
        });
        Schema::table('hr_onboardings', function (Blueprint $table) {
            $table->foreign('onboarding_course_id')->references('id')->on('hr_onboarding_courses');
        });

         // Account 
         Schema::table('act_travel_req_lines', function (Blueprint $table) {
            $table->foreign('act_travel_req_id')->references('id')->on('act_travel_reqs');
            $table->foreign('travel_place_pair_id')->references('id')->on('act_travel_place_pairs');
        });
        Schema::table('act_travel_expense_claim_lines', function (Blueprint $table) {
            $table->foreign('currency_id')->references('id')->on('act_currencies');
            $table->foreign('counter_currency_id')->references('id')->on('act_currencies');
            $table->foreign('travel_expense_claim_id')->references('id')->on('act_travel_expense_claims');
            $table->foreign('currency_pair_id')->references('id')->on('act_currency_pairs');
            $table->foreign('rate_exchange_month_id')->references('id')->on('act_currency_xrs');
        });
        Schema::table('act_currency_xr_lines', function (Blueprint $table) {
            $table->foreign('currency_pair_id')->references('id')->on('act_currency_pairs');
        });
        Schema::table('act_travel_expense_claims', function (Blueprint $table) {
            $table->foreign('advance_req_id')->references('id')->on('act_advance_reqs');
            $table->foreign('travel_req_id')->references('id')->on('act_travel_reqs');
            $table->foreign('currency1_id')->references('id')->on('act_currencies');
            $table->foreign('currency_pair1_id')->references('id')->on('act_currency_pairs');
            $table->foreign('currency2_id')->references('id')->on('act_currencies');
            $table->foreign('currency_pair2_id')->references('id')->on('act_currency_pairs');
            $table->foreign('counter_currency_id')->references('id')->on('act_currencies');
            $table->foreign('rate_exchange_month_id')->references('id')->on('act_currency_xrs');
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
