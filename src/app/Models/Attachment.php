<?php

namespace App\Models;

use App\BigThink\HasProperties;
use App\BigThink\ModelExtended;

class Attachment extends ModelExtended
{
    use HasProperties;

    protected $fillable = [
        "url_folder",
        "url_thumbnail",
        "extension",
        "mime_type",
        "url_media",
        "filename",
        "category",
        "owner_id",
        'object_id',
        'object_type',
    ];
    protected $table = 'attachments';
    public static $nameless = true;
    protected static $statusless = true;

    public static $eloquentParams = [
        "getCategory" => ['belongsTo', Field::class, 'category'],
        "attachable" => ['morphTo', Attachment::class, 'object_type', 'object_id'],
    ];

    public function getCategory()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function attachable()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'filename' => $this->title,
        ];
    }
}
