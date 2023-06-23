<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Eco_taken_action extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "eco_sheet_id", "discipline_id",
    ];
    protected $table = "eco_taken_actions";
    protected static $statusless = true;

    public $eloquentParams = [];

    public $oracyParams = [];


    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'eco_sheet_id',],
            ['dataIndex' => 'discipline_id',],
        ];
    }
}
