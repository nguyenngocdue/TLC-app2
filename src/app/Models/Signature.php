<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Signature extends ModelExtended
{
    protected $fillable = [
        "id", "value", "user_id", "owner_id",
        'category', 'signable_type', 'signable_id',
        'signature_comment', 'signature_decision',
    ];

    public static $nameless = true;
    public function getNameAttribute($value)
    {
        $color = $this->signature_decision == 'approved' ? "green" : "pink";
        $decision = "<div title='#{$this->id}' class='bg-$color-300 text-$color-700 w-1/2 p-2 font-bold rounded text-center my-1'>" . strtoupper($this->signature_decision) . "</div>";
        $avatarUser = "<b>Inspector Comment: </b><br/>";
        return $decision . $avatarUser . $this->signature_comment;
    }

    public static $statusless = true;

    public static $eloquentParams = [
        "getCategory" => ['belongsTo', Field::class, 'category'],
        "signable" => ['morphTo', Comment::class, 'commentable_type', 'commentable_id'],
        "getUser" => ["belongsTo", User::class, "user_id"],
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
    public function getUser()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
