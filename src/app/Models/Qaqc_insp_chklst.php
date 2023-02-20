<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_chklst extends ModelExtended
{
    protected $fillable = ["id", "prod_order_id", "name", "description", "owner_id", "slug", 
    "consent_number", "qaqc_insp_tmpl_id", "progress", "owner_id"];
    protected $table = "qaqc_insp_chklsts";

    public $eloquentParams = [
        "prodOrder" => ["belongsTo", Prod_order::class, "prod_order_id"],
        "getSheets" => ["hasMany", Qaqc_insp_chklst_sht::class, "qaqc_insp_chklst_id"],
        "getQaqcInspTmpl" => ["belongsTo", Qaqc_insp_tmpl::class, "qaqc_insp_tmpl_id"],
        "getOwnerId" => ["belongsTo", User::class, "owner_id"],
        // "getUser" => ["belongsTo", User::class, "owner_id"],
    ];

    public function prodOrder()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getQaqcInspTmpl()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSheets()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    
    public function getManyLineParams()
    {
        return [
            ["dataIndex" => "id"],
            ["dataIndex" => "name"],
            ["dataIndex" => "progress"],
            ["dataIndex" => "prod_order_id"],
            ["dataIndex" => "qaqc_insp_tmpl_id"],
            // ["dataIndex" => "owner_id"],
        ];
    }
}
