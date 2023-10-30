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

        $schema->create('esg_sheet_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->unsignedBigInteger('esg_sheet_id');
            $table->unsignedBigInteger('esg_tmpl_id')->nullable();

            $table->unsignedBigInteger('esg_metric_type_id')->nullable();
            // $table->unsignedBigInteger('esg_metric_type_1_id')->nullable();
            // $table->unsignedBigInteger('esg_metric_type_2_id')->nullable();

            $table->string('esg_code')->nullable();
            $table->unsignedBigInteger('esg_state')->nullable();
            $table->unsignedBigInteger('unit')->nullable();
            // $table->decimal('factor', 10, 4)->nullable();
            $table->double('value')->nullable();
            // $table->double('total')->nullable();
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('esg_sheet_lines');
    }
};
