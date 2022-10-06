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
    public function up()
    {
        Schema::create(Str::plural('user').'_'.Str::plural('post'), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('post_id');
            $table->timestamps();
            $table->unique(['user_id', 'post_id']);
            $table->foreign('user_id')->references('id')->on(Str::plural('user'))->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on(Str::plural('post'))->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Str::plural('user').'_'.Str::plural('post'));
    }
};
