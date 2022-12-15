<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{

    protected function checkRelationShip()
    {
        $relationship = 'rel_2';
        if (strlen($relationship) > 1) {
            $name = md5(Str::plural('zunit_test_2') . '_' . Str::plural('qaqc_insp_value'));
            $tableName = Str::plural('zunit_test_2') . '_' . Str::plural('qaqc_insp_value') . '_' . $relationship;
        } else {
            $name = null;
            $tableName = Str::plural('zunit_test_2') . '_' . Str::plural('zunit_test_2');
        }
        return [$tableName, $name];
    }

    public function up()
    {
        Schema::create($this->checkRelationShip()[0], function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zunit_test_2_id');
            $table->unsignedBigInteger('qaqc_insp_value_id');
            $table->unique(['zunit_test_2_id', 'qaqc_insp_value_id'], $this->checkRelationShip()[1]);

            $table->foreign('zunit_test_2_id')->references('id')->on(Str::plural('zunit_test_2'))->onDelete('cascade');
            $table->foreign('qaqc_insp_value_id')->references('id')->on(Str::plural('qaqc_insp_control_value'))->onDelete('cascade');
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
        Schema::dropIfExists($this->checkRelationShip()[0]);
    }
};
