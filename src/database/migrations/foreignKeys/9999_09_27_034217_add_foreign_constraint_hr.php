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
            $table->foreign('hr_overtime_request_id')->references('id')->on('hr_overtime_requests')->cascadeOnDelete()->cascadeOnUpdate();
        });
        Schema::table('user_team_ots', function (Blueprint $table) {
            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('def_assignee')->references('id')->on('users');
        });
        Schema::table('user_team_tshts', function (Blueprint $table) {
            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('def_assignee')->references('id')->on('users');
        });

        Schema::table('hr_onboarding_lines', function (Blueprint $table) {
            $table->foreign('hr_onboarding_id')->references('id')->on('hr_onboardings');
        });
        Schema::table('hr_onboardings', function (Blueprint $table) {
            $table->foreign('onboarding_course_id')->references('id')->on('hr_onboarding_courses');
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
