<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Sub_project extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;
    public $timestamps = false;
    protected $fillable = ["id", "name", "description", "sub_project_status_id", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'sub_projects';
    // protected $with = [
    // "productionOrders",
    // "subProjectStatus",
    // ];

    public $eloquentParams = [
        "productionOrders" => ['hasMany', Prod_order::class],
        "subProjectStatus" => ['belongsTo', Sub_project_status::class, 'sub_project_status_id'],
    ];
    public function productionOrders()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }
    public function subProjectStatus()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
