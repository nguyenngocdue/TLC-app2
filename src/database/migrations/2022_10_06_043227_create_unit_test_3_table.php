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
        Schema::create('unit_test_3', function (Blueprint $table) {
            $table->id();
            $table->dateTime('datetime1')->nullable();
            $table->dateTime('datetime2')->nullable();
            $table->dateTime('datetime3')->nullable();
            $table->dateTime('datetime4')->nullable();
            $table->dateTime('datetime5')->nullable();
            $table->dateTime('datetime6')->nullable();
            $table->dateTime('datetime7')->nullable();
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
        Schema::dropIfExists('unit_test_3');
    }
};
