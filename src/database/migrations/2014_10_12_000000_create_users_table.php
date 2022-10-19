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
        Schema::create("users", function (Blueprint $table) {
            $table->id();
            $table->string("email")->unique();
            $table->string("name_rendered");
            $table->string("full_name");
            $table->string("name_suffix")->nullable();
            $table->string("employeeid")->nullable();
            $table->string("first_name");
            $table->string("last_name");
            $table->string("address")->nullable();
            $table->string("phone")->nullable();
            $table->unsignedBigInteger("featured_image")->nullable();
            $table->unsignedBigInteger("time_keeping_type")->default(1);
            $table->unsignedBigInteger("user_type")->nullable();
            $table->unsignedBigInteger("workplace")->nullable();
            $table->unsignedBigInteger("category")->nullable();
            $table->date("date_of_birth")->nullable();
            $table->date("first_date")->nullable();
            $table->date("last_date")->nullable();
            $table->string("title")->nullable();
            $table->unsignedBigInteger("position_prefix")->nullable();
            $table->unsignedBigInteger("position_1")->nullable();
            $table->unsignedBigInteger("position_2")->nullable();
            $table->unsignedBigInteger("position_3")->nullable();
            $table->string("position_rendered")->default("");
            $table->unsignedBigInteger("discipline")->nullable();
            $table->unsignedBigInteger("department")->nullable();
            $table->boolean("show_on_beta")->nullable(); //->default(false);
            $table->boolean("resigned")->nullable();
            $table->string("viewport_uids")->nullable();
            $table->string("leaf_uids")->nullable();
            $table->string("user_id_passport")->nullable();
            $table->string("user_pin")->nullable();
            $table->timestamp("email_verified_at")->nullable();
            $table->string("password");
            $table->json("settings");
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
