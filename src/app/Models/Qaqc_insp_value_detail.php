<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Qaqc_insp_value_detail extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["id", "name", "description"];
    protected $table = "qaqc_insp_value_details";

    public $eloquentParams = [
        "valueDetails" => ["hasMany", Qaqc_insp_checklist_line::class, 'value_detail_id']
    ];
}
