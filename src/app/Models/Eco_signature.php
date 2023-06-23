<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Eco_signature extends ModelExtended
{
    protected $fillable = ["id", "value", "owner_id", "eco_sheet_id"];
    protected $table = "eco_signatures";

    public $nameless = true;
    protected static $statusless = true;

    public $eloquentParams = [
        "getSheet" => ["belongsTo", Eco_sheet::class, "eco_sheet_id"],
    ];
    public function getSheet()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            ['dataIndex' => 'eco_sheet_id', "rendererParam" => 'description'],
            ['dataIndex' => 'value'],
            ['dataIndex' => 'owner_id', 'renderer' => 'avatar_user'],
            ['dataIndex' => 'created_at'],
        ];
    }
}
