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

        $schema->create('diginet_business_trip_sheets', function (BlueprintExtended $table) {

            $table->id();
            $table->string('tb_document_id')->nullable();
            $table->string('tb_type')->nullable();
            $table->string('employeeid')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('company_code')->nullable();
            $table->string('workplace_code')->nullable();

            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();

            $table->decimal('total_of_tb_day', 20, 2)->nullable();
            $table->string('tb_project')->nullable();
            $table->string('tb_reason')->nullable();
            $table->string('tb_note')->nullable();
            $table->string('tb_document_status')->nullable();
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
        Schema::dropIfExists('diginet_business_trip_sheets');
    }
};
