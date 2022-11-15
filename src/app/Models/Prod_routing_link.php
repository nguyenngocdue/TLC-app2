<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Notifications\Notifiable;


class Prod_routing_link extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;

    public $timestamps = true;
    protected $fillable = ["id", "name", "parent", "description", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'prod_routing_links';
    // protected $with = [
    // "routings",
    // "productionRun",
    // "discipline",
    // ];

    public $eloquentParams = [
        "routings" => ['belongsToMany', Prod_routing::class, 'prod_routing_details', 'routing_link_id', 'routing_id'],
        "productionRun" => ['hasMany', Prod_run::class, 'prod_routing_link_id'],
        "discipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],
    ];
    public function routings()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('target_hours', 'target_man_hours');
    }

    public function productionRun()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function discipline()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
