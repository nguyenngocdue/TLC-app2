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
        Schema::create('hse_corrective_actions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->string('priority_id');
            $table->unsignedBigInteger('hse_incident_report_id');
            $table->unsignedBigInteger('work_area_id');
            $table->unsignedBigInteger('assignee')->nullable();
            $table->string('unsafe_action_type_id');
            $table->string('status')->nullable();
            $table->dateTime('opened_date')->nullable();
            $table->dateTime('closed_date')->nullable();

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
        Schema::dropIfExists('hse_corrective_actions');
    }
};
