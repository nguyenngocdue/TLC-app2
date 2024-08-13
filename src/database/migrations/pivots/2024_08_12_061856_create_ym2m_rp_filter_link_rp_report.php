<?php

use App\BigThink\BlueprintExtended;
use App\BigThink\TraitCreatePivotTable;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use TraitCreatePivotTable;
    private $table1Plural = 'rp_filter_links';
    private $table2Plural = 'rp_reports';
    private $relationshipKey = null;
    private $tableName = "rp_report_filter_link_details";

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
        $table->orderable();
        $table->unsignedBigInteger('deleted_by')->nullable();
        $table->softDeletes();
    }
};
