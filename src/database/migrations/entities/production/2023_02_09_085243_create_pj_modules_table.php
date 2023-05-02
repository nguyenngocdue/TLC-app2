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
            $table->appendCommonFields();
        });
        // Schema::create('pj_modules', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->text('description')->nullable();
        //     $table->string('slug')->unique();
        //     $table->unsignedBigInteger('pj_building_id')->nullable();
        //     $table->unsignedBigInteger('pj_level_id')->nullable();
        //     $table->unsignedBigInteger('pj_module_type_id')->nullable();
        //     $table->unsignedBigInteger('pj_name_id')->nullable();
        //     $table->unsignedBigInteger('pj_character_id')->nullable();
        //     $table->unsignedBigInteger('pj_unit_id')->nullable();
        //     $table->unsignedBigInteger('pj_shipment_id')->nullable();
        //     $table->unsignedBigInteger('owner_id');
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
        Schema::dropIfExists('pj_modules');
    }
};
