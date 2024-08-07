<?php

namespace App\Models;

use App\BigThink\HasProperties;
use App\BigThink\ModelExtended;

class Attachment extends ModelExtended
{
    use HasProperties;

    protected $fillable = [
        "url_folder", "url_thumbnail",
        "extension", "mime_type",
        "url_media", "filename",
        "category", "sub_category",
        'object_id', 'object_type',
        "owner_id",
    ];
    public static $nameless = true;
    public static $statusless = true;

    public static $eloquentParams = [
        "getCategory" => ['belongsTo', Field::class, 'category'],
        "getSubCategory" => ['belongsTo', Term::class, 'sub_category'],
        "attachable" => ['morphTo', Attachment::class, 'object_type', 'object_id'],
    ];

    public function getCategory()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubCategory()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function attachable()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }
}
