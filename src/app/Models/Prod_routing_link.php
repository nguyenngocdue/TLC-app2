<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_routing_link extends ModelExtended
{
    public $timestamps = true;
    protected $fillable = ["id", "name", "parent", "description", "slug", 'prod_discipline_id', 'owner_id'];
    protected $primaryKey = 'id';
    protected $table = 'prod_routing_links';

    public $eloquentParams = [
        "prodRoutings" => ['belongsToMany', Prod_routing::class, 'prod_routing_details', 'prod_routing_link_id', 'prod_routing_id'],
        "prodSequence" => ['hasMany', Prod_sequence::class, 'prod_routing_link_id'],
        "discipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],
        "getOwnerId" => ['belongsTo', User::class, 'owner_id'],
    ];

    public function prodRoutings()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('target_hours', 'target_man_hours');
    }

    public function prodSequence()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function discipline()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'prod_discipline_id',],
        ];
    }
}
