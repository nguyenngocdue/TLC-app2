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
    protected $primaryKey = 'id';
    protected $table = 'attachments';
    public $nameless = true;

    public $eloquentParams = [
        "getOwnerId" => ['belongsTo', User::class, 'owner_id'],
        "getCategory" => ['belongsTo', Field::class, 'category'],
        "attachable" => ['morphTo', Attachment::class, 'object_type', 'object_id'],
    ];

    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getCategory()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function attachable()
    {
        $p = $this->eloquentParams[__FUNCTION__];
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
