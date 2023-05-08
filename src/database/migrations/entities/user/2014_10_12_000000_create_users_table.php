<?php

use App\BigThink\BlueprintExtended;
use Illuminate\Database\Migrations\Migration;
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
        $schema = DB::connection()->getSchemaBuilder();
        $schema->blueprintResolver(function ($table, $callback) {
            return new BlueprintExtended($table, $callback);
        });

        $schema->create('users', function (BlueprintExtended $table) {
            $table->id();
            $table->string("email")->unique();
            $table->string("name");
            $table->string("full_name");
            $table->string("name_suffix")->nullable();
            $table->string("employeeid")->nullable();
            $table->string("first_name");
            $table->string("last_name");
            $table->string("address")->nullable();
            $table->string("phone")->nullable();
            $table->unsignedBigInteger("time_keeping_type")->default(1);
            $table->unsignedBigInteger("user_type")->nullable();
            $table->unsignedBigInteger("workplace")->nullable();
            $table->unsignedBigInteger("category")->nullable();
            $table->unsignedBigInteger("company")->nullable();
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
            $table->string("provider")->nullable();
            $table->string("password")->nullable();
            $table->string("time_zone")->nullable();
            $table->json("settings");
            $table->rememberToken();
            $table->appendCommonFields(false);
        });
        // Schema::create("users", function (Blueprint $table) {
        //     $table->id();
        //     $table->string("email")->unique();
        //     $table->string("name");
        //     $table->string("full_name");
        //     $table->string("name_suffix")->nullable();
        //     $table->string("employeeid")->nullable();
        //     $table->string("first_name");
        //     $table->string("last_name");
        //     $table->string("address")->nullable();
        //     $table->string("phone")->nullable();
        //     // $table->string("featured_image")->nullable();
        //     $table->unsignedBigInteger("time_keeping_type")->default(1);
        //     $table->unsignedBigInteger("user_type")->nullable();
        //     $table->unsignedBigInteger("workplace")->nullable();
        //     $table->unsignedBigInteger("category")->nullable();
        //     $table->date("date_of_birth")->nullable();
        //     $table->date("first_date")->nullable();
        //     $table->date("last_date")->nullable();
        //     $table->string("title")->nullable();
        //     $table->unsignedBigInteger("position_prefix")->nullable();
        //     $table->unsignedBigInteger("position_1")->nullable();
        //     $table->unsignedBigInteger("position_2")->nullable();
        //     $table->unsignedBigInteger("position_3")->nullable();
        //     $table->string("position_rendered")->default("");
        //     $table->unsignedBigInteger("discipline")->nullable();
        //     $table->unsignedBigInteger("department")->nullable();
        //     $table->boolean("show_on_beta")->nullable(); //->default(false);
        //     $table->boolean("resigned")->nullable();
        //     $table->string("viewport_uids")->nullable();
        //     $table->string("leaf_uids")->nullable();
        //     $table->string("user_id_passport")->nullable();
        //     $table->string("user_pin")->nullable();
        //     $table->timestamp("email_verified_at")->nullable();
        //     $table->string("provider")->nullable();
        //     $table->string("password")->nullable();
        //     $table->string("time_zone")->nullable();
        //     $table->json("settings");
        //     $table->rememberToken();
        //     $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     // $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        // });
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
