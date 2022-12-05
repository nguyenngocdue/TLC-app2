<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Prod_routing_detail extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["prod_routing_id", "prod_routing_link_id", "erp_routing_id", "wir_description_id", "target_hours", "target_man_hours"];
    protected $table = "prod_routing_details";

    public $eloquentParams = [
        "erpRoutingLink" => ['belongsTo', Erp_routing_link::class, 'erp_routing_link_id'],
        "wirDescription" => ['belongsTo', Wir_description::class, 'wir_description_id'],
    ];

    public function erpRoutingLink()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function wirDescription()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
