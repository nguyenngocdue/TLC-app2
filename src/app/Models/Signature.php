<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Signature extends ModelExtended
{
    protected $fillable = ["id", "value", "user_id", "owner_id", 'category', 'signable_type', 'signable_id', 'signature_comment'];
    protected $table = "signatures";

    public static $nameless = true;
    protected static $statusless = true;

    public static $eloquentParams = [
        "getCategory" => ['belongsTo', Field::class, 'category'],
        "signable" => ['morphTo', Comment::class, 'commentable_type', 'commentable_id'],
    ];

    public function getCategory()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function signable()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }
}
