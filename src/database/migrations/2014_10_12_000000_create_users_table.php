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
        Schema::create("users", function (Blueprint $table) {
            $table->id();
            $table->string("name_rendered");
            $table->string("full_name");
            $table->string("name_suffix")->nullable();
            $table->string("employeeid")->nullable();
            $table->string("first_name");
            $table->string("last_name");
            $table->string("address")->nullable();
            $table->string("phone")->nullable();
            $table->unsignedBigInteger("featured_image")->nullable();
            $table->string("time_keeping_type");
            $table->string("user_type");
            $table->unsignedBigInteger("workplace");
            $table->string("category");
            $table->date("date_of_birth")->nullable();
            $table->date("first_date")->nullable();
            $table->date("last_date")->nullable();
            $table->string("title")->nullable();
            $table->unsignedBigInteger("position_prefix")->nullable();
            $table->unsignedBigInteger("position_1")->nullable();
            $table->unsignedBigInteger("position_2");
            $table->unsignedBigInteger("position_3")->nullable();
            $table->string("position_rendered");
            $table->unsignedBigInteger("role");
            $table->unsignedBigInteger("discipline");
            $table->unsignedBigInteger("department")->nullable();
            $table->boolean("show_on_beta");
            $table->boolean("resigned")->nullable();
            $table->string("viewpost_uids")->nullable();
            $table->string("leaf_uids")->nullable();
            $table->string("email")->unique();
            $table->timestamp("email_verified_at")->nullable();
            $table->string("password");
            $table->rememberToken();
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
        Schema::dropIfExists("users");
    }
};
