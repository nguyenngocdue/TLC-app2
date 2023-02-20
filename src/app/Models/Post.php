<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Post extends ModelExtended
{
    protected $fillable = ["name", "content", "owner_id"];
    protected $primaryKey = 'id';
    protected $table = 'posts';

    public $eloquentParams = [
        "getOwnerId" => ['belongsTo', User::class, 'owner_id'],
    ];

    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
