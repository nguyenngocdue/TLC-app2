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

        $schema->create('diginet_employee_overtime_sheets', function (BlueprintExtended $table) {

            $table->id();
            $table->string('tb_document_id')->nullable();
            $table->string('employeeid')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('company_code')->nullable();
            $table->string('workplace_code')->nullable();
            $table->date('ot_date')->nullable();
            $table->string('ot_type')->nullable();
            $table->decimal('ot_hours', 20, 2)->nullable();
            $table->unsignedBigInteger('ot_projects')->nullable();
            $table->string('ot_reason')->nullable();
            $table->string('approver_id')->nullable();
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
        Schema::dropIfExists('diginet_employee_overtime_sheets');
    }
};
