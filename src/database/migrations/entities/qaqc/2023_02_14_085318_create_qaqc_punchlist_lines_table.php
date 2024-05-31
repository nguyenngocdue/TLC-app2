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

        $schema->create('qaqc_punchlist_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->text('prod_discipline_id')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('qaqc_punchlist_id')->nullable();
            $table->text('remark')->nullable();
            $table->string('material_supply')->nullable();
            $table->string('scope')->nullable();
            $table->boolean('is_original_scope')->nullable();
            $table->orderable();
            $table->hasStatus();
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
        Schema::dropIfExists('qaqc_punchlist_lines');
    }
};
