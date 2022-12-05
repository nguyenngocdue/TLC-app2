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
    protected $fillable = ["id", "qaqc_insp_chklst_id", "qaqc_insp_master_id", "name", "description", "control", "value"];
    protected $table = "qaqc_insp_chklst_lines";

    public $eloquentParams = [
        "valueDetails" => ["belongsTo",]
    ];
}
