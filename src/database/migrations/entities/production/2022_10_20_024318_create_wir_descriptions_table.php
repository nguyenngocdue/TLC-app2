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

        $schema->create('wir_descriptions', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('short_desc')->nullable();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('prod_discipline_id')->nullable();
            $table->unsignedInteger('wir_weight');
            $table->unsignedBigInteger('def_assignee');
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
        Schema::dropIfExists('wir_descriptions');
    }
};
