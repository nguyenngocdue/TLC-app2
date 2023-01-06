<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_tmpl_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "control_type_id", "qaqc_insp_tmpl_run_id",
        "qaqc_insp_group_id", "qaqc_insp_control_group_id",
    ];
    protected $table = "qaqc_insp_tmpl_lines";

    public $eloquentParams = [
        // "getTemplate" => ["belongsTo", Qaqc_insp_tmpl::class, "qaqc_insp_tmpl_id"],
        // "getSheet" => ["belongsTo", Qaqc_insp_sheet::class, "qaqc_insp_sheet_id"],
        "getGroup" => ["belongsTo", Qaqc_insp_group::class, "qaqc_insp_group_id"],
        "getRun" => ["belongsTo", Qaqc_insp_tmpl_run::class, "qaqc_insp_tmpl_run_id"],
        "getControlType" => ["belongsTo", Control_type::class, "control_type_id"],
        "getControlGroup" => ["belongsTo", Qaqc_insp_control_group::class, "qaqc_insp_control_group_id"],
    ];

    public function getRun()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getGroup()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlType()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlGroup()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', "renderer" => "id", "align" => "center", "type" => "qaqc_insp_tmpl_lines"],
            ['dataIndex' => 'getTemplate', 'title' => "Template", 'renderer' => "column", "rendererParam" => "description"],
            ['dataIndex' => 'getSheet', 'title' => "Sheet", 'renderer' => "column", "rendererParam" => "description"],
            ['dataIndex' => 'getGroup', "title" => "Group", 'renderer' => "column", "rendererParam" => "description"],
            ['dataIndex' => 'getControlType', "title" => "Control Type", 'renderer' => "column", "rendererParam" => "name"],
            ['dataIndex' => 'getControlGroup', "title" => "Control Group", 'renderer' => "column", "rendererParam" => "name"],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
        ];
    }
}
