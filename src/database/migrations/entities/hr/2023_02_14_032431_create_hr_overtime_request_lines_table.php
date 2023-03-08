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
        Schema::create('hr_overtime_request_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("hr_overtime_request_id");
            $table->unsignedBigInteger("user_id")->nullable();
            $table->string("employeeid")->nullable();
            $table->string("position_rendered")->nullable();
            $table->date("ot_date")->nullable();
            $table->time("from_time")->nullable();
            $table->time("to_time")->nullable();
            $table->double("break_time")->nullable();
            $table->float("total_time")->nullable();
            $table->float("allowed_hours")->nullable();
            $table->float("remaining_hours")->nullable();
            $table->unsignedBigInteger("sub_project_id")->nullable();
            $table->unsignedBigInteger("work_mode_id")->nullable();
            $table->text("remark")->nullable();
            $table->unsignedInteger('order_no')->nullable();
            $table->unsignedBigInteger('owner_id');
            $table->string('status')->nullable();
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
        Schema::dropIfExists('hr_overtime_request_lines');
    }
};
