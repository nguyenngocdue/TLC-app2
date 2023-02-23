<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_overtime_request_line extends ModelExtended
{
    protected $fillable = [
        "id", "hr_overtime_request_id", "user_id", "employeeid", "position_rendered",
        "ot_date", "from_time", "to_time", "break_time", "order_no", "owner_id",
        "total_time", "sub_project_id", "work_mode_id", "remark"
    ];
    protected $table = "hr_overtime_request_lines";
    public $nameless = true;

    public $eloquentParams = [
        "getHROvertimeRequest" => ['belongsTo', Hr_overtime_request::class, 'hr_overtime_request_id'],
        "getUserID" => ['belongsTo', User::class, 'user_id'],
        "getSubProject" => ['belongsTo', Sub_project::class, 'sub_project_id'],
        "getWorkMode" => ['belongsTo', Work_mode::class, 'work_mode_id'],
        "getOwnerId" => ['belongsTo', User::class, 'owner_id'],
    ];

    public function getHROvertimeRequest()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
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
            ['dataIndex' => 'ot_date'],
            ['dataIndex' => 'from_time'],
            ['dataIndex' => 'to_time'],
            ['dataIndex' => 'break_time'],
            ['dataIndex' => 'total_time'],
            ['dataIndex' => 'sub_project_id'],
            ['dataIndex' => 'work_mode_id'],
            ['dataIndex' => 'remark'],
        ];
    }
}
