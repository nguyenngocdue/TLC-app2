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

        $schema->create('eco_effectiveness_lines', function (BlueprintExtended $table) {
            $table->id();
            // $table->string('name')->nullable();
            $table->text('description')->nullable(); //Remark
            $table->unsignedBigInteger('eco_sheet_id');
            $table->unsignedBigInteger('change_effectiveness_id')->nullable();
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
        Schema::dropIfExists('eco_effectiveness_lines');
    }
};
