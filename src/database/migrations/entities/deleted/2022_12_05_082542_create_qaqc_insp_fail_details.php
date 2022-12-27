<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected function checkRelationShip()
    {
        $relationship = 'rel_1';
        if (strlen($relationship) > 1) {
            $name = md5(Str::plural('qaqc_insp_chklst_line') . '_' . Str::plural('qaqc_insp_value'));
            // $tableName = Str::plural('qaqc_insp_chklst_line') . '_' . Str::plural('qaqc_insp_value') . '_' . $relationship;
        } else {
            $name = null;
            // $tableName = Str::plural('qaqc_insp_chklst_line') . '_' . Str::plural('qaqc_insp_value');
        }
        $tableName = "qaqc_insp_fail_details";
        return [$tableName, $name];
    }
    public function up()
    {

        Schema::create($this->checkRelationShip()[0], function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('qaqc_insp_chklst_line_id');
            $table->unsignedBigInteger('qaqc_insp_value_id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));;
            $table->unique(['qaqc_insp_chklst_line_id', 'qaqc_insp_value_id'], $this->checkRelationShip()[1]);
            $table->foreign('qaqc_insp_chklst_line_id', "qaqc_insp_fail_id")->references('id')->on(Str::plural('qaqc_insp_chklst_lines'))->onDelete('cascade');
            $table->foreign('qaqc_insp_value_id', "qaqc_insp_value_fail_id")->references('id')->on(Str::plural('qaqc_insp_values'))->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->checkRelationShip()[0]);
    }
};
