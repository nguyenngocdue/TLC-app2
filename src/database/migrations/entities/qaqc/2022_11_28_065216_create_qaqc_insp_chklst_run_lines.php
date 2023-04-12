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
        Schema::create('qaqc_insp_chklst_run_lines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('control_type_id');
            $table->text('value')->nullable();
            $table->text('value_on_hold')->nullable();
            $table->text('value_comment')->nullable();
            // $table->unsignedBigInteger('qaqc_insp_chklst_sht_id');
            $table->unsignedBigInteger('qaqc_insp_chklst_run_id');
            $table->unsignedBigInteger('qaqc_insp_group_id');
            $table->unsignedBigInteger('qaqc_insp_control_value_id')->nullable();
            $table->unsignedBigInteger('qaqc_insp_control_group_id')->nullable();
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('inspector_id')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qaqc_insp_chklst_run_lines');
    }
};
