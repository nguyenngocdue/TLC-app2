<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_car extends ModelExtended
{
    protected $fillable = ["qaqc_ncr_id", "responsible_person", "remark", "cause_analysis", 
    "corrective_action", "order_no"];
    protected $table = "qaqc_cars";
    public $nameless = true;

    public $eloquentParams = [
        "getQaqcNcr" => ['belongsTo', Qaqc_ncr::class, 'qaqc_ncr_id'],
        "getResponsiblePerson" => ['belongsTo', User::class, 'responsible_person'],
        
    ];

    public function getQaqcNcr()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getResponsiblePerson()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true],
            ["dataIndex" => 'id'],
            ['dataIndex' => 'qaqc_ncr_id', 'title' => 'NCR ID', 'invisible' => true,],
            ['dataIndex' => 'responsible_person'],
            ['dataIndex' => 'cause_analysis'],
            ['dataIndex' => 'corrective_action'],
            ['dataIndex' => 'remark'],
        ];
    }
}
