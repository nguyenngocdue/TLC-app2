<?php

use App\BigThink\TraitCreatePivotTable;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use TraitCreatePivotTable;
    private $table1Plural = 'qaqc_insp_tmpl_shts';
    private $table2Plural = 'users';
    private $relationshipKey = 'def_ext_insp_1';

    public function up()
    {
        $this->schemaUp();
    }

    public function down()
    {
        $this->schemaDown();
    }
};
