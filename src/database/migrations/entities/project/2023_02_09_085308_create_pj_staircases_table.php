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

        $schema->create('pj_staircases', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('pj_building_id')->nullable();
            $table->unsignedBigInteger('pj_level_id')->nullable();
            $table->unsignedBigInteger('pj_module_type_id')->nullable();
            $table->unsignedBigInteger('pj_name_id')->nullable();
            $table->unsignedBigInteger('pj_character_id')->nullable();
            $table->unsignedBigInteger('pj_shipment_id')->nullable();
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
        Schema::dropIfExists('pj_staircases');
    }
};
