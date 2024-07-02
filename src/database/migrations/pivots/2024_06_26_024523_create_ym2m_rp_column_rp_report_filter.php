<?php

use App\BigThink\BlueprintExtended;
use App\BigThink\TraitCreatePivotTable;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use TraitCreatePivotTable;
    private $table1Plural = 'rp_columns';
    private $table2Plural = 'rp_reports';
    private $relationshipKey = 'filter';

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
        $table->unsignedInteger('col_span')->nullable();
        $table->string('bw_list_ids')->nullable();
        $table->unsignedBigInteger('black_or_white')->nullable();
        $table->boolean('is_required')->nullable();
        $table->string('default_value')->nullable();
        $table->boolean('has_listen_to')->nullable();
        $table->boolean('allow_clear')->nullable();
        $table->boolean('is_multiple')->nullable();
        $table->unsignedBigInteger('control_type')->nullable();
    }
};
