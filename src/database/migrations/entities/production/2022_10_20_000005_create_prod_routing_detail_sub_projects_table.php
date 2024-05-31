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

        $schema->create('prod_routing_detail_sub_projects', function (BlueprintExtended $table) {
            $table->id();

            $table->unsignedBigInteger('sub_project_id');
            $table->unsignedBigInteger('prod_routing_detail_id');
            $table->unsignedBigInteger('prod_routing_id')->nullable();
            $table->unsignedBigInteger('prod_routing_link_id')->nullable();

            $table->unsignedInteger("sheet_count")->nullable();
            $table->double("avg_man_power")->nullable();
            $table->double("avg_total_uom")->nullable();
            $table->double("avg_min")->nullable();
            $table->double("avg_min_uom")->nullable();

            $table->appendCommonFields();

            $table->unique(['sub_project_id', 'prod_routing_detail_id'], md5('sub_project_id' . 'prod_routing_detail_id'));
            $table->foreign('sub_project_id')->references('id')->on('sub_projects')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('prod_routing_detail_id')->references('id')->on('prod_routing_details')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod_routing_detail_sub_projects');
    }
};
