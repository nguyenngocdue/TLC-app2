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
            $table->unsignedBigInteger('radio_yes_no');
            $table->unsignedBigInteger('radio_pass_fail');
            $table->unsignedBigInteger('dropdown_yes_no');
            $table->unsignedBigInteger('dropdown_pass_fail');
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
