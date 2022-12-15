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
        Schema::create('zunit_test_2s', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->unsignedBigInteger('Radio_Yes/No');
            $table->unsignedBigInteger('Radio_Pass/Fail');
            $table->unsignedBigInteger('Dropdown_Yes/No');
            $table->unsignedBigInteger('Dropdown_Pass/Fail');
            $table->unsignedBigInteger('Checkbox_Yes/No');
            $table->unsignedBigInteger('Checkbox_Pass/Fail');
            $table->unsignedBigInteger('Dropdown-multi_Yes/No');
            $table->unsignedBigInteger('Dropdown-multi_Pass/Fail');
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
        Schema::dropIfExists('zunit_test_2s');
    }
};
