<?php

use App\BigThink\BlueprintExtended;
use App\BigThink\TraitCreatePivotTable;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use TraitCreatePivotTable;
    private $table1Plural = 'diginet_business_trip_lines';
    private $table2Plural = 'fin_expense_claims';
    private $relationshipKey = null;
    private $tableName = "fin_expense_claim_travel_details";
    private $table1IdColumn = 'finger_print';

    public function up()
    {
        $this->schemaUp();
    }

    public function down()
    {
        $this->schemaDown();
    }

    public function schemaPivot(BlueprintExtended $table)
    {
        $table->unsignedInteger('day_count')->nullable();
        $table->date('travel_date')->nullable();
        $table->string('travel_reason')->nullable();
        $table->string('employee_id')->nullable();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->orderable();
        $table->unsignedBigInteger('deleted_by')->nullable();
        $table->softDeletes();
    }
};
