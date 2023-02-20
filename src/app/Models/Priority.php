<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Priority extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "duration", "field_id", "owner_id"];
    protected $primaryKey = 'id';
    protected $table = 'priorities';

    public $eloquentParams = [
        "getField" => ['belongsTo', Field::class, 'field_id'],
        "getOwnerId" => ['belongsTo', User::class, 'owner_id'],
    ];

    public function getField()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
