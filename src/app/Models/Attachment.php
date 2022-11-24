<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Notifications\Notifiable;

class Attachment extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["url_folder", "url_thumbnail", "extension", "url_media", "filename", "category", "owner_id"];
    protected $primaryKey = 'id';
    protected $table = 'attachments';
    // protected $with = [
    //     'user',
    // ];
    public $eloquentParams = [
        "user" => ['belongsTo', User::class, 'owner_id'],
        "category1" => ['belongsTo', Media_category::class, 'category'],
    ];

    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function category1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'filename' => $this->title,
        ];
    }

    public function mediable()
    {
        return $this->morphTo();
    }
}
