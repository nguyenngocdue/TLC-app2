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
    // "prodRun",
    // "discipline",
    // ];

    public $eloquentParams = [
        "prodRoutings" => ['belongsToMany', Prod_routing::class, 'prod_routing_details', 'prod_routing_link_id', 'prod_routing_id'],
        "prodRun" => ['hasMany', Prod_run::class, 'prod_routing_link_id'],
        "discipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],
    ];
    public function prodRoutings()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('target_hours', 'target_man_hours');
    }

    public function prodRun()
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
