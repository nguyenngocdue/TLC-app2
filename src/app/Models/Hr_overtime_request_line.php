<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_overtime_request_line extends ModelExtended
{
    protected $fillable = [
        "id", "hr_overtime_request_id", "user_id", "employeeid", "position_rendered",
        "ot_date", "from_time", "to_time", "break_time", "order_no", "owner_id",
        "total_time", "sub_project_id", "work_mode_id", "remark", "status", "rt_remaining_hours"
    ];
    protected $table = "hr_overtime_request_lines";
    public $nameless = true;

    public $eloquentParams = [
        "getHROvertimeRequest" => ['belongsTo', Hr_overtime_request::class, 'hr_overtime_request_id'],
        "getUserID" => ['belongsTo', User::class, 'user_id'],
        "getSubProject" => ['belongsTo', Sub_project::class, 'sub_project_id'],
        "getWorkMode" => ['belongsTo', Work_mode::class, 'work_mode_id'],
        "getOwnerId" => ['belongsTo', User::class, 'owner_id'],
        "getRemainingHours" => ['hasOne', View_otr_remaining::class, 'user_id', 'user_id'],
        "getRemainingHoursList" => ['hasMany', View_otr_remaining::class, 'user_id', 'user_id'],
    ];

    public function getRemainingHours()
    {
        $month = substr($this->ot_date, 0, 7);
        $relation = $this->hasOne(View_otr_remaining::class, 'user_id', 'user_id');
        $sql = $relation
            ->getQuery()
            ->where('view_otr_remainings.month', 'LIKE', $month)
            ->toSql();
        // var_dump($month, $sql);
        return $relation;
    }

    public function getHROvertimeRequest()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getRemainingHoursList()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3]);
        // var_dump($relation->toSql());
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getUserID()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWorkMode()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true],
            ["dataIndex" => 'id', 'title' => 'OT Line ID'],
            ['dataIndex' => 'hr_overtime_request_id', 'title' => 'OT ID', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'user_id', 'title' => 'Full Name', 'value_as_user_id' => true],
            ['dataIndex' => 'employeeid'],
            ['dataIndex' => 'position_rendered', 'title' => 'Position'],
            ['dataIndex' => 'ot_date', 'cloneable' => true],
            ['dataIndex' => 'from_time', 'cloneable' => true],
            ['dataIndex' => 'to_time', 'cloneable' => true],
            ['dataIndex' => 'break_time', 'cloneable' => true],
            ['dataIndex' => 'total_time'],
            ['dataIndex' => 'sub_project_id', 'cloneable' => true],
            ['dataIndex' => 'work_mode_id', 'cloneable' => true],
            ['dataIndex' => 'remark'],
            ['dataIndex' => 'status', 'cloneable' => true],
        ];
    }
}
