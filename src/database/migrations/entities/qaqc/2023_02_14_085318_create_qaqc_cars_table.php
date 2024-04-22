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

        $schema->create('qaqc_cars', function (BlueprintExtended $table) {
            $table->id();
            $table->unsignedBigInteger('qaqc_ncr_id');
            $table->text('cause_analysis')->nullable();
            $table->text('corrective_action')->nullable();
            $table->unsignedBigInteger('responsible_person')->nullable();
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
        Schema::dropIfExists('qaqc_cars');
    }
};
