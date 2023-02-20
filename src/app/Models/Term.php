<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Term extends ModelExtended
{
    protected $fillable = [
        'id',
        'name',
        'description',
        'slug',
        'field_id',
        'parent1_id',
        'parent2_id',
        'owner_id'
    ];
    protected $table = "terms";

    public $eloquentParams = [
        'getField' => ['belongsTo', Field::class, 'field_id'],
        "getOwnerId" => ["belongsTo", User::class, "owner_id"],
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
