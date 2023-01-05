<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\Http\Traits\HasStatus;

class Hse_corrective_action extends ModelExtended
{
    use HasStatus;
    public $menuTitle = "HSE Corrective Actions";
    protected $fillable = [
        'id', 'name', 'description', 'slug', 'hse_incident_report_id', 'priority_id', 'work_area_id',
        'assignee', 'opened_date', 'closed_date', 'status', 'unsafe_action_type_id',
    ];
    protected $table = "hse_corrective_actions";

    public $eloquentParams = [
        'getHseIncidentReport' => ['belongsTo', Hse_incident_report::class, 'hse_incident_report_id'],
        'getWorkArea' => ['belongsTo', Work_area::class, 'work_area_id'],
        'getAssignee' => ['belongsTo', User::class, 'assignee'],
    ];

    public function getHseIncidentReport()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getWorkArea()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', 'renderer' => 'id', 'type' => 'hse_corrective_actions', 'align' => 'center'],
            ['dataIndex' => 'name', 'title' => 'Name'],
            ['dataIndex' => 'priority_id', 'title' => 'Priority ID'],
            ['dataIndex' => 'getWorkArea', 'title' => 'Work Area', 'renderer' => 'column', 'rendererParam' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'getAssignee', 'title' => 'Assignee', 'renderer' => 'column', 'rendererParam' => 'name'],
            ['dataIndex' => 'opened_date'],
            ['dataIndex' => 'closed_date'],
            ['dataIndex' => 'status', "renderer" => "status", "align" => "center"],
            ['dataIndex' => 'unsafe_action_type_id', 'title' => 'Unsafe Action Type'],
        ];
    }
}
