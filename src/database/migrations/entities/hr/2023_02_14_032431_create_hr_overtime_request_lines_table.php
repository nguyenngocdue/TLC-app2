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
            $table->unsignedBigInteger("user_id");
            $table->string("employeeid");
            $table->string("position_rendered");
            $table->date("ot_date")->nullable();
            $table->time("from_time");
            $table->time("to_time");
            $table->double("break_time");
            $table->float("total_time");
            $table->unsignedBigInteger("sub_project_id");
            $table->unsignedBigInteger("work_mode_id");
            $table->text("remark")->nullable();
            $table->unsignedInteger('order_no')->nullable();
            $table->unsignedBigInteger('owner_id');
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
