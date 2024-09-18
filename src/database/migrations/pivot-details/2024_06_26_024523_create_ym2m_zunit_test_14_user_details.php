<?php

use App\BigThink\BlueprintExtended;
use App\BigThink\TraitCreatePivotTable;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use TraitCreatePivotTable;
    private $table1Plural = 'zunit_test_14s';
    private $table2Plural = 'users';
    private $relationshipKey = null;
    private $tableName = "zunit_test_14_user_details";

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
        $table->unsignedBigInteger('deleted_by')->nullable();
        $table->softDeletes();
    }
};
