<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_chklst_sht_sig extends ModelExtended
{
    protected $fillable = ["id", "value", "owner_id", "qaqc_insp_chklst_sht_id"];
    protected $table = "qaqc_insp_chklst_sht_sigs";

    public $eloquentParams = [];


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
