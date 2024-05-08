<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_run extends ModelExtended
{
    protected $fillable = [
        "id", "prod_sequence_id", "date", "start", "end", "owner_id",
        "total_hours", "total_man_hours",
        "worker_number", "worker_number_count", "worker_number_input",
        "remark", "production_output", "is_rework",

    ];

    public static $nameless = true;
    public static $statusless = true;

    public static $eloquentParams = [
        "getProdSequence" => ['belongsTo', Prod_sequence::class, 'prod_sequence_id'],
        "getUsers" => ['belongsToMany', User::class, 'prod_user_runs', 'prod_run_id', 'user_id'],
    ];

    public static $oracyParams = [
        'getWorkersOfRun()' => ['getCheckedByField', User::class,],
        'getItemsOfProdOutput()' => ['getCheckedByField', Prod_ppr_item::class,],
    ];

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('user_id');
    }

    public function getProdSequence()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWorkersOfRun()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getItemsOfProdOutput()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    // private function isNZ($parentItem)
    // {
    //     $a = $parentItem->getRoutingsHaveWorkersOfRun();
    //     if (in_array($parentItem->prod_routing_id, $a)) return true;
    //     return false;
    // }

    private function needToShowProdOutputQty($parentItem)
    {
        $a = $parentItem->getRoutingsNeedProdOutputQty();
        if (in_array($parentItem->prod_routing_id, $a)) return true;

        $a = $parentItem->getDisciplinesNeedProdOutputQty();
        if (in_array($parentItem->prod_discipline_id, $a)) return true;
        return false;
    }

    public function getManyLineParams($parentItem)
    {
        // $isNZ = $this->isNZ($parentItem);
        // echo "IS NZ: $isNZ";
        $needToShowProdOutputQty = $this->needToShowProdOutputQty($parentItem);
        // echo "IS PPR: $needToShowProdOutputQty";

        $result = [
            ['dataIndex' => 'id', 'invisible' => !true,],
            ['dataIndex' => 'prod_sequence_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'date', /*'cloneable' => true,*/ 'footer' => 'agg_none'],
            ['dataIndex' => 'start', /*'cloneable' => true,*/],
            ['dataIndex' => 'end', /*'cloneable' => true,*/],
            ['dataIndex' => 'total_hours', 'footer' => 'agg_sum', /*'no_print' => true,*/],
        ];
        // if ($isNZ) {
        // $result[] = ['dataIndex' => 'getWorkersOfRun()', 'cloneable' => true,];
        // } else {
        $result[] = ['dataIndex' => 'worker_number_input', 'footer' => 'agg_avg',];
        // }
        $result = [
            ...$result,
            ['dataIndex' => 'worker_number_count', 'invisible' => true,],
            ['dataIndex' => 'worker_number',  'footer' => 'agg_avg', 'no_print' => true,],
            ['dataIndex' => 'total_man_hours', 'footer' => 'agg_sum',/* 'no_print' => true,*/],
        ];
        if ($needToShowProdOutputQty) {
            $result[] = ['dataIndex' => 'production_output',  'footer' => 'agg_sum', /*'no_print' => true,*/];
            // $result[] = ['dataIndex' => 'getItemsOfProdOutput()', 'no_print' => true,];
        }
        $result = [
            ...$result,
            ['dataIndex' => 'remark', /*'no_print' => true,*/],
            ['dataIndex' => 'is_rework', "invisible" => true],
        ];

        return $result;
    }

    public function getManyLineParamsRework($parentItem)
    {
        $columns = $this->getManyLineParams($parentItem);

        foreach ($columns as $key => $column) {
            // dump($key, $column);
            if ($column['dataIndex'] == 'remark') {
                $column['required'] = true;
            }
        }
        return $columns;
    }
}
