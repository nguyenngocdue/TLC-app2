<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_3 extends ModelExtended
{
    protected $fillable = ["datetime1", "datetime2", "datetime3", "datetime4", "datetime5", "datetime6", "datetime7"];
    protected $primaryKey = 'id';
    protected $table = 'zunit_test_3s';
    public $menuTitle = "UT03 (DateTime Controls)";

    public $eloquentParams = [];
}
