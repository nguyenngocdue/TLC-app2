<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Prod_discipline extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;

    public $fillable = ["id", "name", "description", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'prod_disciplines';
    public $timestamps = true;
    protected $with = [
        'routingLink',
    ];

    public $eloquentParams = [
        "routingLink" => ['hasMany', Prod_routing_link::class, 'prod_discipline_id'],
    ];
    public function routingLink()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
