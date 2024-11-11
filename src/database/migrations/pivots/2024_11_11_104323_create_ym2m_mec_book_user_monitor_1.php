<?php

use App\BigThink\TraitCreatePivotTable;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use TraitCreatePivotTable;
    private $table1Plural = 'mec_books';
    private $table2Plural = 'users';
    private $relationshipKey = 'monitor_1';

    public function up()
    {
        $this->schemaUp();
    }

    public function down()
    {
        $this->schemaDown();
    }
};
