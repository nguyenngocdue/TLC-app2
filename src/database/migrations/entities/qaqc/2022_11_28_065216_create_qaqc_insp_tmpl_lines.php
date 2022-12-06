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
        Schema::create('qaqc_insp_tmpl_lines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('control_type');
            $table->unsignedBigInteger('qaqc_insp_tmpl_id');
            $table->unsignedBigInteger('qaqc_insp_chklst_sheet_id');
            $table->unsignedBigInteger('qaqc_insp_chklst_group_id');
            // $table->unsignedBigInteger('prod_routing_id');
            // $table->unsignedBigInteger('wir_description_id');
            // $table->unique(['prod_routing_id', 'wir_description_id']);

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
        Schema::dropIfExists('qaqc_insp_tmpl_lines');
    }
};
