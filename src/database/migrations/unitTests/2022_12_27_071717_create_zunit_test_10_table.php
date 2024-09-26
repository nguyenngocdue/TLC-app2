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
        $schema->blueprintResolver(fn($table, $callback) => new BlueprintExtended($table, $callback));

        $schema->create('zunit_test_10s', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();

            $table->unsignedBigInteger('prod_routing_1_id')->nullable();
            $table->unsignedBigInteger('prod_routing_2_id')->nullable();

            $table->unsignedBigInteger('sub_project_5a_id')->nullable();
            $table->unsignedBigInteger('prod_routing_5_id')->nullable();

            $table->unsignedBigInteger('sub_project_6a_id')->nullable();
            $table->unsignedBigInteger('prod_routing_6_id')->nullable();

            $table->unsignedBigInteger('sub_project_7a_id')->nullable();
            $table->unsignedBigInteger('sub_project_8a_id')->nullable();

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
        Schema::dropIfExists('zunit_test_10s');
    }
};
