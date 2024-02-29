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

        $schema->create('diginet_employee_leave_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->string('employeeid')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('company_code')->nullable();
            $table->string('workplace_code')->nullable();
            $table->string('la_type')->nullable();
            $table->dateTime('la_date')->nullable();

            $table->float('number_of_la_day')->nullable();
            $table->string('la_reason')->nullable();
            $table->unsignedBigInteger('la_document_id')->nullable();
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->string('approver_name')->nullable();


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
        Schema::dropIfExists('diginet_employee_leave_lines');
    }
};
