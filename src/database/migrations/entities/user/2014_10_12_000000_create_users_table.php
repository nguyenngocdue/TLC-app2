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
            $table->string("name")->nullable();
            $table->string("name0")->default("New User");
            $table->string("full_name");
            $table->string("name_suffix")->nullable();
            $table->string("employeeid")->nullable();
            $table->string("first_name");
            $table->string("last_name");
            $table->boolean('gender')->nullable();
            $table->string("address")->nullable();
            $table->string("phone")->nullable();

            $table->unsignedBigInteger("time_keeping_type")->default(1);
            $table->unsignedBigInteger("user_type")->nullable();
            $table->unsignedBigInteger("workplace")->nullable();
            $table->unsignedBigInteger("current_workplace")->nullable();
            $table->unsignedBigInteger("category")->nullable();
            $table->unsignedBigInteger("company")->nullable();
            $table->date("date_of_birth")->nullable();
            $table->date("first_date")->nullable();
            $table->date("last_date")->nullable();
            $table->date("leave_effective_date")->nullable();
            $table->string("title")->nullable();

            $table->unsignedBigInteger('position')->nullable();
            $table->unsignedBigInteger("discipline")->nullable();
            $table->unsignedBigInteger("org_chart")->nullable();
            $table->unsignedBigInteger("department")->nullable();

            $table->unsignedBigInteger("erp_sub_cat")->nullable();
            $table->unsignedBigInteger("erp_site")->nullable();
            $table->unsignedBigInteger("erp_cashflow")->nullable();

            $table->boolean("show_on_beta")->nullable();
            $table->boolean("is_bod")->nullable();
            $table->boolean("resigned")->nullable();
            $table->string("viewport_uids")->nullable();
            $table->string("leaf_uids")->nullable();
            $table->string("user_id_passport")->nullable();
            $table->string("user_pin")->nullable();
            $table->timestamp("email_verified_at")->nullable();
            $table->string("provider")->nullable();
            $table->string("password")->nullable();
            $table->string("time_zone")->nullable();
            $table->text("standard_signature")->nullable();
            $table->json("settings");
            $table->rememberToken();
            $table->appendCommonFields();
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
