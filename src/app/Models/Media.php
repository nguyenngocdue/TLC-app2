<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Media extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["url_folder", "url_thumbnail", "extension", "url_media", "filename", "category", "owner_id"];
    protected $primaryKey = 'id';
    protected $table = 'media';
    // protected $with = [
    //     'user',
    // ];
    public $eloquentParams = [
        "user" => ['belongsTo', User::class, 'owner_id'],
        // "category" => ['belongsTo', Media_category::class, 'category'],
    ];
    public function user()
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
