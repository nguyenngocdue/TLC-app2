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

        $schema->create('zunit_test_02s', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();

            $table->unsignedBigInteger('radio_yes_no')->nullable();
            $table->unsignedBigInteger('radio_pass_fail')->nullable();
            $table->unsignedBigInteger('dropdown_yes_no')->nullable();
            $table->unsignedBigInteger('dropdown_pass_fail')->nullable();

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->orderable();
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
        Schema::dropIfExists('zunit_test_02s');
    }
};
