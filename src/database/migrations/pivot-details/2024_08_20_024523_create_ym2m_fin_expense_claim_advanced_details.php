<?php

use App\BigThink\BlueprintExtended;
use App\BigThink\TraitCreatePivotTable;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use TraitCreatePivotTable;
    private $table1Plural = 'act_advance_reqs';
    private $table2Plural = 'fin_expense_claims';
    private $relationshipKey = null;
    private $tableName = "fin_expense_claim_adv_details";

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
        $table->double('adv_amount_lcy')->nullable();
        $table->unsignedBigInteger('adv_currency_id')->nullable();
        $table->date('adv_date')->nullable();
        $table->string('adv_reason')->nullable();
        $table->string('employee_id')->nullable();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->orderable();
        $table->unsignedBigInteger('deleted_by')->nullable();
        $table->softDeletes();
    }
};
