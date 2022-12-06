<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Qaqc_insp_chklst_line extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = [
        "id", "name", "description", "control_type", "value",
        "qaqc_insp_chklst_id", "qaqc_insp_chklst_sheet_id", "qaqc_insp_chklst_group_id",
    ];
    protected $table = "qaqc_insp_chklst_lines";

    public $eloquentParams = [
        "getChklst" => ["belongsTo", Qaqc_insp_chklst::class, "qaqc_insp_chklst_id"],
        "getSheet" => ["belongsTo", Qaqc_insp_chklst_sheet::class, "qaqc_insp_chklst_sheet_id"],
        "getGroup" => ["belongsTo", Qaqc_insp_chklst_group::class, "qaqc_insp_chklst_group_id"],
        // "hasDetails" => ["belongsToMany", Qaqc_insp_value],
    ];

    public function getChklst()
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
}
