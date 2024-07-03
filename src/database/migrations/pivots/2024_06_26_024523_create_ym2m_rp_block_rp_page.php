<?php

use App\BigThink\BlueprintExtended;
use App\BigThink\TraitCreatePivotTable;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use TraitCreatePivotTable;
    private $table1Plural = 'rp_blocks';
    private $table2Plural = 'rp_pages';
    private $relationshipKey = null;
    private $tableName = "rp_page_block_details";

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
        $table->integer('col_span')->nullable();
        $table->orderable();
        $table->unsignedBigInteger('deleted_at')->nullable();
        $table->unsignedBigInteger('deleted_by')->nullable();
    }
};
