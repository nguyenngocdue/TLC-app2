<?php

use App\BigThink\BlueprintExtended;
use Illuminate\Database\Migrations\Migration;
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
        $schema = DB::connection()->getSchemaBuilder();
        $schema->blueprintResolver(fn ($table, $callback) => new BlueprintExtended($table, $callback));

        $schema->create('hr_timesheet_officer_lines', function (BlueprintExtended $table) {
            $table->id();
            // $table->string("timesheetable_type")->nullable();
            // $table->unsignedBigInteger("timesheetable_id")->nullable();
            $table->foreignId("hr_timesheet_officer_id")->constrained()->onUpdate("cascade")->onDelete("cascade");
            $table->unsignedBigInteger("user_id")->nullable();
            // $table->date("ts_date")->nullable();
            // $table->float("ts_hour")->nullable();
            $table->dateTime("start_time")->nullable();
            $table->float("duration_in_min")->nullable();
            // $table->float("duration_in_hour")->nullable();
            $table->unsignedBigInteger("project_id")->nullable();
            $table->unsignedBigInteger("sub_project_id")->nullable();
            // $table->unsignedBigInteger("prod_routing_id")->nullable();
            $table->unsignedBigInteger("lod_id")->nullable();
            $table->unsignedBigInteger("discipline_id")->nullable();
            $table->unsignedBigInteger("task_id")->nullable();
            $table->unsignedBigInteger("sub_task_id")->nullable();

            $table->unsignedBigInteger("work_mode_id")->nullable();
            $table->text("remark")->nullable();

            $table->orderable();
            $table->appendCommonFields();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_timesheet_officer_lines');
    }
};
