<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Qaqc_insp_control_group extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["id", "name", "description", "slug"];
    protected $table = "qaqc_insp_control_groups";

    public $eloquentParams = [
        "getChklstLines" => ["hasMany", Qaqc_insp_chklst_line::class, "qaqc_insp_control_group_id"],
        "getControlValues" => ["hasMany", Qaqc_insp_control_value::class, "qaqc_insp_control_group_id"],
    ];
    public function getChklstLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlValues()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
