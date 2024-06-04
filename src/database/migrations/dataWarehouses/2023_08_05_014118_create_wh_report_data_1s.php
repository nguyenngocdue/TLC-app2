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
        $schema->blueprintResolver(fn ($table, $callback) => new BlueprintExtended($table, $callback));

        $schema->create('wh_report_data_1s', function (BlueprintExtended $table) {
            $table->id();
            $table->date('month');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('sub_project_id');
            $table->unsignedBigInteger('prod_routing_id');
            $table->integer('quantity');
            $table->float('progress');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wh_report_data_1s');
    }
};
