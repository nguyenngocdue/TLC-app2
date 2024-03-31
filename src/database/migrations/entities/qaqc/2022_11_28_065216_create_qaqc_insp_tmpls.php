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

        $schema->create('qaqc_insp_tmpls', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('short_name')->nullable();
            $table->boolean('has_punchlist')->default(false);

            // $table->unsignedBigInteger('prod_routing_id');
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
        Schema::dropIfExists('qaqc_insp_tmpls');
    }
};
