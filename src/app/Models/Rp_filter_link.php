<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_filter_link extends ModelExtended
{
    protected $fillable = ["id", "name", "title", "linked_to_report_id", "stored_filter_key", "owner_id"];

    public static $eloquentParams = [];



    // public function getManyLineParams()
    // {
    //     return [
    //         ["dataIndex" => "id", /* "invisible" => true, */],
    //         ["dataIndex" => "name"],
    //         ["dataIndex" => "linked_to_report_id"],
    //         ["dataIndex" => "stored_filter_key"]
    //     ];
    // }
}
