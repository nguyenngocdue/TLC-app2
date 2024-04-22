<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Field extends ModelExtended
{
    protected $fillable = ['id', 'name', 'reversed_name', 'description', 'owner_id'];
    public static $statusless = true;

    public static $eloquentParams = [
        'getTerms' => ['hasMany', Term::class, 'field_id'],

        //__FUNCTION__ is not dynamic
        // "getAttachments" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public function getTerms()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    // public function getAttachments()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    //     return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    // }
}
