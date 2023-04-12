<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_chklst_sht extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "qaqc_insp_chklst_id", "qaqc_insp_tmpl_sht_id", "owner_id"];
    protected $table = "qaqc_insp_chklst_shts";

    public $eloquentParams = [
        "getRuns" => ["hasMany", Qaqc_insp_chklst_run::class, "qaqc_insp_chklst_sht_id"], // version 1
        "getLines" => ["hasMany", Qaqc_insp_chklst_line::class, "qaqc_insp_chklst_sht_id"],
        "getChklst" => ["belongsTo", Qaqc_insp_chklst::class, 'qaqc_insp_chklst_id'],
        "getTmplSheet" => ["belongsTo", Qaqc_insp_tmpl_sht::class, 'qaqc_insp_tmpl_sht_id'],
        "getOwnerId" => ["belongsTo", User::class, "owner_id"],
    ];

    public function getRuns()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2]);
        $relation
            ->getQuery()
            ->orderBy('created_at', 'DESC')
            ->toSql();
        return $relation;
    } // version 1

    public function getLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    } // version 2

    public function getChklst()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTmplSheet()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getProject()
    {
        $tmp = $this->getChklst->prodOrder->subProject;
        $relation = $tmp->belongsTo(Project::class, 'project_id');
        return $relation;
    }

    public function getSubProject()
    {
        $tmp = $this->getChklst->prodOrder;
        $relation = $tmp->belongsTo(Sub_project::class, 'sub_project_id');
        return $relation;
    }
    public function getProdOrder()
    {
        $tmp = $this->getChklst;
        $relation = $tmp->belongsTo(Prod_order::class, 'prod_order_id');
        return $relation;
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'qaqc_insp_chklst_id'],
            ['dataIndex' => 'qaqc_insp_tmpl_sht_id'],
        ];
    }
}
