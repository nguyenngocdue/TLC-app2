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

        $schema->create('ghg_tmpl_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('ghg_tmpl_id')->nullable();

            $table->unsignedBigInteger('ghg_metric_type_id')->nullable();
            $table->unsignedBigInteger('ghg_metric_type_1_id')->nullable();
            $table->unsignedBigInteger('ghg_metric_type_2_id')->nullable();
            $table->unsignedBigInteger('unit')->nullable();
            $table->decimal('factor', 10, 4)->nullable();

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
        Schema::dropIfExists('ghg_tmpl_lines');
    }
};
