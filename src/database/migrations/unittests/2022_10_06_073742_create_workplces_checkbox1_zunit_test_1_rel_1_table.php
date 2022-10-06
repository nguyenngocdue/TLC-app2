<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
            $name = md5(Str::plural('zunit_test_1') . '_' . Str::plural('workplace'));
            $tableName = Str::plural('zunit_test_1') . '_' . Str::plural('workplace') . '_' . $relationship;
        } else {
            $name = null;
            $tableName = Str::plural('zunit_test_1') . '_' . Str::plural('workplace');
        }
        return [$tableName, $name];
    }
    public function up()
    {

        Schema::create($this->checkRelationShip()[0], function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zunit_test_1_id');
            $table->unsignedBigInteger('workplace_id');
            $table->timestamps();
            $table->unique(['zunit_test_1_id', 'workplace_id'], $this->checkRelationShip()[1]);
            $table->foreign('zunit_test_1_id')->references('id')->on(Str::plural('zunit_test_1'))->onDelete('cascade');
            $table->foreign('workplace_id')->references('id')->on(Str::plural('workplace'))->onDelete('cascade');
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
