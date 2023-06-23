<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Eco_effectiveness_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "remark", "eco_sheet_id", "term_id"
    ];
    protected $table = "eco_effectiveness_lines";
    protected static $statusless = true;

    public $eloquentParams = [];

    public $oracyParams = [];


    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'remark',],
            ['dataIndex' => 'eco_sheet_id',],
            ['dataIndex' => 'term_id',],
        ];
    }
}
