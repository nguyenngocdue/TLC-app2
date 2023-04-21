<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_chklst_sht_sig extends ModelExtended
{
    protected $fillable = ["id", "value", "owner_id", "qaqc_insp_chklst_sht_id"];
    protected $table = "qaqc_insp_chklst_sht_sigs";

    public $nameless = true;

    public $eloquentParams = [
        "getOwnerId" => ["belongsTo", User::class, "owner_id"],
    ];
    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }


    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            ['dataIndex' => 'value'],
            ['dataIndex' => 'owner_id'],
            ['dataIndex' => 'qaqc_insp_chklst_sht_id'],
        ];
    }
}
