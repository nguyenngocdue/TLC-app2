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
        Schema::create('create_users_workplaces_table', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('workplace_id');
            $table->timestamps();
            $table->unique(['user_id', 'workplace_id']);
            $table->foreign('user_id')->references('id')->on(Str::plural('user'))->onDelete('cascade');
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
        Schema::dropIfExists('create_users_workplaces_table');
    }
};
