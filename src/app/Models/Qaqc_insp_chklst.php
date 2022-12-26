<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_chklst extends ModelExtended
{
    protected $fillable = ["id", "prod_order_id", "name", "description", "owner_id", "slug", "consent_number", "progress"];
    protected $table = "qaqc_insp_chklsts";

    public $eloquentParams = [
        "user" => ["belongsTo", User::class, "owner_id"],
        "prodOrder" => ["belongsTo", Prod_order::class, "prod_order_id"],
        "qaqcInspChklstLines" => ["hasMany", Qaqc_insp_chklst_line::class, "qaqc_insp_chklst_id"],
    ];
    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function prodOrder()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function qaqcInspChklstLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => "id", "renderer" => "id", "type" => "qaqc_insp_chklsts"],
            ["dataIndex" => "name"],
            ["dataIndex" => "progress"],
        ];
    }
}
