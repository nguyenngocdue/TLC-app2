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

        $schema->create('hr_overtime_request_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->unsignedBigInteger("hr_overtime_request_id");
            $table->unsignedBigInteger("user_id")->nullable();
            $table->string("employeeid")->nullable();
            $table->string("position_rendered")->nullable();
            $table->unsignedBigInteger("position_id")->nullable();
            $table->date("ot_date")->nullable();
            $table->time("from_time")->nullable();
            $table->time("to_time")->nullable();
            $table->double("break_time")->nullable();
            $table->float("total_time")->nullable();
            $table->float("month_allowed_hours")->nullable();
            $table->float("month_remaining_hours")->nullable();
            $table->float("year_allowed_hours")->nullable();
            $table->float("year_remaining_hours")->nullable();
            $table->unsignedBigInteger("sub_project_id")->nullable();
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
        Schema::dropIfExists('hr_overtime_request_lines');
    }
};
