<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Wir_description extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["name", 'description', 'slug'];
    protected $table = "wir_descriptions";

    public $eloquentParams = [
        "prodRoutingDetails" => ["hasMany", Prod_routing_detail::class, "wir_description_id",]
    ];

    public function prodRoutingDetails()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
