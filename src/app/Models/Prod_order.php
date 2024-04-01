<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_order extends ModelExtended
{
    protected $fillable = [
        "id", "slug", "name", "status", "description", "quantity",
        "sub_project_id", "prod_routing_id", "status", "meta_type", "meta_id", "owner_id",
        "started_at", "finished_at", "total_hours", "total_man_hours", "room_type_id",
        "production_name", "compliance_name", "erp_name", "sheet_count",
        "total_calendar_days", "no_of_sundays", "no_of_ph_days", "total_days_no_sun_no_ph", "total_days_have_ts", "total_discrepancy_days",
        "prod_sequence_progress",
    ];

    public static $eloquentParams = [
        "getSubProject" => ['belongsTo', Sub_project::class, 'sub_project_id'],
        "getProdRouting" => ['belongsTo', Prod_routing::class, 'prod_routing_id'],
        "getRoomType" => ['belongsTo', Term::class, 'room_type_id'],

        "getProdSequences" => ['hasMany', Prod_sequence::class, 'prod_order_id'],
        "getQaqcInspChklsts" => ['hasMany', Qaqc_insp_chklst::class, 'prod_order_id'],

        "getMeta" => ['morphTo', Prod_order::class, 'meta_type', 'meta_id'],
    ];

    public function getSubProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMeta()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

    public function getProdRouting()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getRoomType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdSequences()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getQaqcInspChklsts()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            ['dataIndex' => 'name', 'title' => 'Priority'],
            ['dataIndex' => 'production_name'],
            ['dataIndex' => 'compliance_name'],
            ['dataIndex' => 'prod_routing_id'],
            ['dataIndex' => 'status'],
            // ['dataIndex' => 'getMeta', 'rendererParam' => "id"],
        ];
    }
}
