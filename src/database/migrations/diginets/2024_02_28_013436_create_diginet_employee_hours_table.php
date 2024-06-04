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

        $schema->create('diginet_employee_hours', function (BlueprintExtended $table) {
            $table->id();
            $table->string('employeeid')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('company_code')->nullable();
            $table->string('workplace_code')->nullable();
            $table->date('date')->nullable();
            $table->float('standard_hours')->nullable();
            $table->float('actual_working_hours')->nullable();
            $table->float('ot_hours')->nullable();
            $table->float('la_hours')->nullable();
            $table->float('business_trip_hours')->nullable();
            $table->float('work_from_home_hours')->nullable();

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
        Schema::dropIfExists('diginet_employee_hours');
    }
};
