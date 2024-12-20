<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use Illuminate\Support\Facades\Log;

class Prod_sequence extends ModelExtended
{
    protected $fillable = [
        "id",
        "prod_order_id",
        "prod_routing_link_id",
        "status",
        "expected_start_at",
        "expected_finish_at",
        "priority",
        "uom_id",
        "owner_id",
        "closed_at",
        "sub_project_id",
        "prod_routing_id",
        "prod_discipline_id",
        "total_uom",
        "uom_input",
        "erp_prod_order_name",

        "start_date_0",
        "start_date_1",
        "start_date_2",
        "start_date_10",
        "start_date_11",
        "start_date",

        "end_date_0",
        "end_date_1",
        "end_date_2",
        "end_date_10",
        "end_date_11",
        "end_date",

        "uom_agg_0",
        "uom_agg_1",
        "uom_agg_2",
        "uom_agg_10",
        "uom_agg_11",
        "uom_agg",

        "total_hours_0",
        "total_hours_1",
        "total_hours_2",
        "total_hours_11",
        "total_hours",

        "worker_number_0",
        "worker_number_1",
        "worker_number_2",
        "worker_number_11",
        "worker_number",

        "total_man_hours_0",
        "total_man_hours_1",
        "total_man_hours_2",
        "total_man_hours_11",
        "total_man_hours",

        "total_calendar_days",
        "no_of_sundays",
        "no_of_ph_days",
        "total_days_no_sun_no_ph",
        "total_days_have_ts",
        "total_discrepancy_days",
    ];

    public static $nameless = true;

    public static $eloquentParams = [
        "getProdOrder" => ['belongsTo', Prod_order::class, 'prod_order_id'],
        "getProdRoutingLinks" => ['belongsTo', Prod_routing_link::class, 'prod_routing_link_id'],
        "getUomId" => ["belongsTo", Term::class, 'uom_id'],

        "getProdRuns" => ['hasMany', Prod_run::class, 'prod_sequence_id'],
        "getProdRunsRework1" => ['hasMany', Prod_run::class, 'prod_sequence_id'],
        "getProdRunsRework2" => ['hasMany', Prod_run::class, 'prod_sequence_id'],
        "getProdRunsSubCon" => ['hasMany', Prod_run::class, 'prod_sequence_id'],
        "getProdRunsSubConPartTime" => ['hasMany', Prod_run::class, 'prod_sequence_id'],

        "getProdRoutingDetails" => ['hasMany', Prod_routing_detail::class, "prod_routing_link_id", "prod_routing_link_id"],
        "getProdRoutingDetail" => ['belongsTo', Prod_routing_detail::class, "prod_routing_id", "prod_routing_id"],

        "getSubProject" => ['belongsTo', Sub_project::class, 'sub_project_id'],
        "getProdRouting" => ['belongsTo', Prod_routing::class, "prod_routing_id"],
        "getProdDiscipline" => ['belongsTo', Prod_discipline::class, "prod_discipline_id"],

        "comment_on_hold_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_cancel_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_na_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
    ];

    //This is temporary design, will finalize when PPR on board.
    public function getRoutingsHaveWorkersOfRun()
    {
        return [52, 55, 56];
    }

    public function getRoutingsNeedProdOutputQty()
    {
        //62: PPR Monthly timesheet, 
        //63: Factory Upgrade -> non production activities,
        return [62];
    }

    public function getDisciplinesNeedProdOutputQty()
    {
        return config('prod_discipline.to_hide');
    }

    public function getProdOrder()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRoutingLinks()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getUomId()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRuns()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2])->where('is_rework', 0);
    }

    public function getProdRunsRework1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2])->where('is_rework', 1);
    }

    public function getProdRunsRework2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2])->where('is_rework', 2);
    }

    public function getProdRunsSubCon()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2])->where('is_rework', 10);
    }

    public function getProdRunsSubConPartTime()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2])->where('is_rework', 11);
    }

    // public function getProdRoutingDetails()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2], $p[3]);
    // }

    public function getSubProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRouting()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdDiscipline()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function comment_on_hold_reason()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function comment_cancel_reason()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function comment_na_reason()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getProdRoutingDetails()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3]);
        $prodOrder = $this->getProdOrder;
        if ($prodOrder) {
            $prod_routing_id = $prodOrder->prod_routing_id;
            $sql = $relation
                ->getQuery()
                ->where('prod_routing_id', $prod_routing_id)
                ->toSql();
            // Log::info($sql);
            return $relation;
        }
        return $relation;
    }

    public function getProdRoutingDetail()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3])
            ->where('prod_routing_link_id', $this->prod_routing_link_id);
        return $relation;
    }


    public function getManyLineParams()
    {
        return [
            ["dataIndex" => "id",],
            ["dataIndex" => "prod_order_id", "deaf" => true,],
            ["dataIndex" => "prod_routing_link_id", "deaf" => true,],

            ["dataIndex" => "total_hours", "footer" => "agg_sum"],
            ["dataIndex" => "worker_number",],
            ["dataIndex" => "total_man_hours", "footer" => "agg_sum"],
            ["dataIndex" => "total_uom",],
            ["dataIndex" => "uom_id", 'read_only_rr2' => true],
            ["dataIndex" => "status", 'read_only_rr2' => true],
        ];
    }

    // public function getManyLineParams1()
    // {
    //     return [
    //         ["dataIndex" => "id",], //"renderer" => "id", "type" => "prod_sequences", "align" => "center"],
    //         ["dataIndex" => "prod_order_id", "title" => "Prod Order Id", "rendererParam" => "id"],
    //         ["dataIndex" => "prod_order_id", "title" => "Routing Id (*)", "rendererParam" => "prod_routing_id"],
    //         ["dataIndex" => "prod_routing_link_id", "title" => "Prod Routing ID", "rendererParam" => "id"],
    //         ["dataIndex" => "prod_routing_link_id",  "title" => "Prod Routing Name (*)", "rendererParam" => "name"],

    //         // ["dataIndex" => "expected_start_at",],
    //         // ["dataIndex" => "expected_finish_at",],

    //         ["dataIndex" => "total_hours",],
    //         ["dataIndex" => "total_man_hours", "title" => "Total ManHours",],
    //         ["dataIndex" => "prod_routing_link_id", "title" => "Target Hours", "rendererParam" => "target_hours"],
    //         ["dataIndex" => "prod_routing_link_id", "title" => "Target ManHours (*)", "rendererParam" => "target_man_hours"],
    //         ["dataIndex" => "status",],
    //     ];
    // }
}
