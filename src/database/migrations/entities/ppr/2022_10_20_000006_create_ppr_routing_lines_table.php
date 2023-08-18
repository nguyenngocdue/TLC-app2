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

        $schema->create('ppr_routing_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->unsignedBigInteger('ppr_routing_id');
            $table->unsignedBigInteger('ppr_routing_link_id')->nullable();
            $table->double('target_hours')->nullable();
            $table->double('target_man_hours')->nullable();
            $table->orderable();
            $table->appendCommonFields();

            $table->unique(['ppr_routing_id', 'ppr_routing_link_id'], md5('ppr_routing_id' . 'ppr_routing_link_id'));
            $table->foreign('ppr_routing_id')->references('id')->on('ppr_routings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ppr_routing_link_id')->references('id')->on('ppr_routing_links')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ppr_routing_lines');
    }
};
