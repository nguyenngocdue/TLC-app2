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
    // private $tableName = "rp_page_block_details";
    private $table1IdColumn = 'finger_print';

    public function up()
    {
        $this->schemaUp();
    }

    public function down()
    {
        $this->schemaDown();
    }
};
