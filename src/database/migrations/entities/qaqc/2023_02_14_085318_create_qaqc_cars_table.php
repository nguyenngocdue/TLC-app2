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
        Schema::create('qaqc_cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qaqc_ncr_id');
            $table->text('cause_analysis');
            $table->text('corrective_action');
            $table->unsignedBigInteger('responsible_person');
            $table->text('remark')->nullable();
            $table->unsignedInteger('order_no')->nullable();
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
        Schema::dropIfExists('qaqc_cars');
    }
};
