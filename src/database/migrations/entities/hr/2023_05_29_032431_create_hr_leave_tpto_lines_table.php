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

        $schema->create('hr_leave_tpto_lines', function (BlueprintExtended $table) {
            $table->id();

            $table->unsignedBigInteger("parent_id")->nullable();
            $table->unsignedBigInteger("workplace_id")->nullable();
            $table->integer("year")->nullable();
            $table->date("starting_date")->nullable();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->string("remark")->nullable();

            $table->float("annual_leave")->nullable();
            $table->float("sick_leave")->nullable();
            $table->float("domestic_violence_leave")->nullable();
            $table->float("cash_out")->nullable();

            $table->orderable();
            // $table->hasStatus();
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
        Schema::dropIfExists('hr_leave_tpto_lines');
    }
};
