<?php

use App\BigThink\TraitCreatePivotTable;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use TraitCreatePivotTable;
    private $table1Plural = 'act_travel_reqs';
    private $table2Plural = 'users';
    private $relationshipKey = 'monitor_2';

    public function up()
    {
        $this->schemaUp();
    }

    public function down()
    {
        $this->schemaDown();
    }
};
