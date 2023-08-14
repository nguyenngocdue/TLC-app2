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
        Schema::table('terms', function (Blueprint $table) {
            $table->foreign('field_id')->references('id')->on('fields');
        });
        Schema::table('many_to_many', function (Blueprint $table) {
            $table->foreign('field_id')->references('id')->on('fields');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('category')->references('id')->on('fields');
        });
        Schema::table('attachments', function (Blueprint $table) {
            $table->foreign('category')->references('id')->on('fields');
        });
        Schema::table('signatures', function (Blueprint $table) {
            $table->foreign('category')->references('id')->on('fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
