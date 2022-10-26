<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('zunit_test_5s', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string('attachment_1');
            $table->string('attachment_2');
            $table->string('attachment_3');
            $table->string('attachment_4');
            $table->string('attachment_5');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zunit_test_5s');
    }
};
