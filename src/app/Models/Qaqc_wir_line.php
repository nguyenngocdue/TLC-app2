<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_wir_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "order_no",
        "qc_total", "qc_accepted", "qc_remaining", "qc_rejected",
    ];
    protected $table = "qaqc_wir_lines";
    public static $nameless = true;
    public static $eloquentParams = [
        "getParent" => ["belongsTo", Qaqc_wir::class, "qaqc_wir_id"],
    ];

    public static $oracyParams = [
    ];

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            ['dataIndex' => 'qaqc_wir_id', 'title' => 'ID', 'invisible' => !true, 'value_as_parent_id' => true],
            ['dataIndex' => 'qc_total',],
            ['dataIndex' => 'qc_accepted',],
            ['dataIndex' => 'qc_remaining',],
            ['dataIndex' => 'qc_rejected',],
        ];
    }
}
