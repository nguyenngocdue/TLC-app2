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

        $schema->create('pj_modules', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('pj_building_id')->nullable();
            $table->unsignedBigInteger('pj_level_id')->nullable();
            $table->unsignedBigInteger('pj_module_type_id')->nullable();
            $table->unsignedBigInteger('pj_name_id')->nullable();
            $table->unsignedBigInteger('pj_character_id')->nullable();
            $table->unsignedBigInteger('pj_unit_id')->nullable();
            $table->unsignedBigInteger('pj_shipment_id')->nullable();

            $table->unsignedBigInteger('sub_project_id')->nullable();
            $table->float('length')->nullable();
            $table->float('width')->nullable();
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->string('plot_number')->nullable();
            $table->unsignedInteger('manufactured_year')->nullable();

            $table->text('insp_chklst_link')->nullable();
            $table->text('shipping_doc_link')->nullable();

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
        Schema::dropIfExists('pj_modules');
    }
};
