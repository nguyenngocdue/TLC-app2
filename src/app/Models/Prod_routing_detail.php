<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Prod_routing_detail extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ['routing_id', 'routing_link_id', 'target_hours', 'target_man_hours'];
    protected $primaryKey = ['routing_id', 'routing_link_id'];
    public $incrementing = false;
    public $timestamps = true;
    protected $table = 'prod_routing_details';
}
