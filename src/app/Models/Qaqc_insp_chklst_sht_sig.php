<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_chklst_sht_sig extends ModelExtended
{
    protected $fillable = ["id", "value", "owner_id", "qaqc_insp_chklst_sht_id"];
    protected $table = "qaqc_insp_chklst_sht_sigs";

    public $nameless = true;

    public $eloquentParams = [
        "getSheet" => ["belongsTo", Qaqc_insp_chklst_sht::class, "qaqc_insp_chklst_sht_id"],
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
            ['dataIndex' => 'qaqc_insp_chklst_sht_id', "rendererParam" => 'description'],
            ['dataIndex' => 'value'],
            ['dataIndex' => 'owner_id', 'renderer' => 'avatar_user'],
            ['dataIndex' => 'created_at'],
        ];
    }
}
