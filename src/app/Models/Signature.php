<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Signature extends ModelExtended
{
    protected $fillable = ["id", "value", "owner_id", 'category', 'signable_type', 'signable_id'];
    protected $table = "signatures";

    public $nameless = true;
    protected static $statusless = true;

    public $eloquentParams = [
        "getCategory" => ['belongsTo', Field::class, 'category'],
        "signable" => ['morphTo', Comment::class, 'commentable_type', 'commentable_id'],
    ];

    public function getCategory()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function signable()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }
}
