<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Qaqc_insp_tmpl_line extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = [
        "id", "name", "description", "control_type",
        "qaqc_insp_tmpl_id", "qaqc_insp_chklst_sheet_id", "qaqc_insp_chklst_group_id",
    ];
    protected $table = "qaqc_insp_tmpl_lines";

    public $eloquentParams = [
        "getTemplate" => ["belongsTo", Qaqc_insp_tmpl::class, "qaqc_insp_tmpl_id"],
        "getSheet" => ["belongsTo", Qaqc_insp_chklst_sheet::class, "qaqc_insp_chklst_sheet_id"],
        "getGroup" => ["belongsTo", Qaqc_insp_chklst_group::class, "qaqc_insp_chklst_group_id"],
    ];

    public function getTemplate()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getSheet()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getGroup()
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
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'control_type'],
        ];
    }
}
