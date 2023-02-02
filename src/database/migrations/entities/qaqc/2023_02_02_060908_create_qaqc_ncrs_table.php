<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('qaqc_ncrs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->string('parent_type')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('sub_project_id')->nullable();
            $table->unsignedBigInteger('prod_routing_id')->nullable();
            $table->unsignedBigInteger('pj_level_id')->nullable();
            $table->unsignedBigInteger('pj_type_id')->nullable();
            $table->unsignedBigInteger('prod_order_id')->nullable();
            $table->unsignedBigInteger('prod_discipline_id')->nullable();
            $table->unsignedBigInteger('prod_discipline_1_id')->nullable();
            $table->unsignedBigInteger('prod_discipline_2_id')->nullable();
            $table->unsignedBigInteger('user_team_id')->nullable();
            $table->unsignedBigInteger('inter_subcon_id')->nullable();
            $table->unsignedBigInteger('priority_id')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('closed_date')->nullable();
            $table->unsignedBigInteger('defect_root_cause_id')->nullable();
            $table->unsignedBigInteger('defect_disposition_id')->nullable();
            $table->text('cause_analysis')->nullable();
            $table->unsignedBigInteger('assignee_to')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qaqc_ncrs');
    }
};
