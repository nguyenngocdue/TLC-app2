<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Qaqc_insp_master_list extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["id", "prod_routing_id", "wir_description_id", "name", "description", "control"];
    protected $table = "qaqc_insp_master_lists";

    public $eloquentParams = [];
}
