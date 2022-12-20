<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Term extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'slug', 'field_id'];
    protected $table = "terms";

    public $eloquentParams = [
        'getField' => ['belongsTo', Field::class, 'field_id'],
    ];

    public function getField()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
