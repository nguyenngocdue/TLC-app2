<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Qaqc_insp_checklist extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["id", "prod_order_id", "wir_description_id", "name", "description", "owner_id"];
    protected $table = "qaqc_insp_checklists";

    public $eloquentParams = [
        "user" => ["belongsTo", User::class, "owner_id"],
        "prodOrder" => ["belongsTo", Prod_order::class, "prod_order_id"],
        "qaqcInspChecklistLines" => ["hasMany", Qaqc_insp_checklist_line::class, "qaqc_insp_checklist_id"],
    ];
    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function prodOrder()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function qaqcInspChecklistLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
