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

        $schema->create('eco_material_impact_adds', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable(); //Item added
            $table->text('description')->nullable(); //Remark
            $table->float('unit_price')->nullable();
            $table->float('quantity')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable(); //->Term
            $table->float('amount')->nullable();
            $table->unsignedBigInteger('eco_sheet_id');
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
        Schema::dropIfExists('eco_material_impact_adds');
    }
};
