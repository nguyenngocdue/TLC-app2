<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Attachment extends ModelExtended
{
    protected $fillable = ["url_folder", "url_thumbnail", "extension", "url_media", "filename", "category", "owner_id"];
    protected $primaryKey = 'id';
    protected $table = 'attachments';

    public $eloquentParams = [
        "user" => ['belongsTo', User::class, 'owner_id'],
        "getCategory" => ['belongsTo', Attachment_category::class, 'category'],
        "mediable" => ['morphTo', 'mediable', 'object_type', 'object_id'],
    ];

    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getCategory()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function mediable()
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
