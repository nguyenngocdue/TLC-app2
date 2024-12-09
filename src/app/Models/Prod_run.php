<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_run extends ModelExtended
{
    protected $fillable = [
        "id",
        "prod_sequence_id",
        "date",
        "start",
        "end",
        "owner_id",
        "total_hours",
        "total_man_hours",
        "worker_number",
        "worker_number_count",
        "worker_number_input",
        "remark",
        "production_output",
        "is_rework",
        "prod_discipline_id",
    ];

    public static $nameless = true;
    public static $statusless = true;

    public static $eloquentParams = [
        "getProdSequence" => ['belongsTo', Prod_sequence::class, 'prod_sequence_id'],
        "getProdDiscipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],
        "getUsers" => ['belongsToMany', User::class, 'prod_user_runs'],

        "getWorkersOfRun" => ['belongsToMany', User::class, 'ym2m_prod_run_user_worker',],
    ];

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdSequence()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWorkersOfRun()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdDiscipline()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    // public function getItemsOfProdOutput()
    // {
    //     $p = static::$oracyParams[__FUNCTION__ . '()'];
    //     return $this->{$p[0]}(__FUNCTION__, $p[1]);
    // }

    // private function isNZ($parentItem)
    // {
    //     $a = $parentItem->getRoutingsHaveWorkersOfRun();
    //     if (in_array($parentItem->prod_routing_id, $a)) return true;
    //     return false;
    // }

    // private function needToShowProdOutputQty($parentItem)
    // {
    //     $a = $parentItem->getRoutingsNeedProdOutputQty();
    //     if (in_array($parentItem->prod_routing_id, $a)) return true;

    //     $a = $parentItem->getDisciplinesNeedProdOutputQty();
    //     if (in_array($parentItem->prod_discipline_id, $a)) return true;
    //     return false;
    // }

    public function getManyLineParams($parentItem)
    {
        $show_team_column = $parentItem?->getSubProject->sqb_input_team;
        $result = [
            ['dataIndex' => 'id', 'invisible' => true,],
            ['dataIndex' => 'prod_sequence_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'date', 'footer' => 'agg_none'],
            ['dataIndex' => 'start',],
            ['dataIndex' => 'end',],
            ['dataIndex' => 'total_hours', 'footer' => 'agg_sum',],
            ['dataIndex' => 'worker_number_input',],
            ['dataIndex' => 'worker_number_count', 'invisible' => true,],
            ['dataIndex' => 'worker_number', 'no_print' => true,],
            ['dataIndex' => 'total_man_hours', 'footer' => 'agg_sum',],
            ['dataIndex' => 'production_output',  'footer' => 'agg_sum',],
            ['dataIndex' => 'prod_discipline_id', 'invisible' => !$show_team_column],
            ['dataIndex' => 'remark',],
            ['dataIndex' => 'is_rework', "invisible" => true],
        ];

        return $result;
    }

    public function getManyLineParamsRework($parentItem)
    {
        $columns = $this->getManyLineParams($parentItem);

        foreach ($columns as &$column) {
            // dump($key, $column);
            if ($column['dataIndex'] == 'remark') {
                $column['required'] = true;
            }
        }
        return $columns;
    }

    public function getManyLineParamsSubCon($parentItem)
    {
        // $columns = $this->getManyLineParams($parentItem);
        $result = [
            ['dataIndex' => 'id', 'invisible' => true,],
            ['dataIndex' => 'prod_sequence_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'date', 'footer' => 'agg_none'],
            ['dataIndex' => 'start',],
            // ['dataIndex' => 'end',],
            // ['dataIndex' => 'total_hours', 'footer' => 'agg_sum',],
            // ['dataIndex' => 'worker_number_input',],
            // ['dataIndex' => 'worker_number_count', 'invisible' => true,],
            // ['dataIndex' => 'worker_number', 'no_print' => true,],
            // ['dataIndex' => 'total_man_hours', 'footer' => 'agg_sum',],
            ['dataIndex' => 'production_output',  'footer' => 'agg_sum',],
            // ['dataIndex' => 'prod_discipline_id', 'invisible' => !$show_team_column],
            ['dataIndex' => 'remark', 'required' => true],
            ['dataIndex' => 'is_rework', "invisible" => true],
        ];
        return $result;
    }
}
