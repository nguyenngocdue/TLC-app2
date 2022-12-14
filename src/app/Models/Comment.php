<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Comment extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ['content', 'position_rendered', 'owner_id', 'category'];
    protected $table = "comments";
    protected $primaryKey = 'id';

    public $eloquentParams = [
        "commentable" => ['morphTo', 'commentable', 'commentable_type', 'commentable_id'],
        "user" => ['belongsTo', User::class, 'owner_id'],
        "getCategory" => ['belongsTo', Comment_category::class, 'category'],
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

    public function commentable()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }
    // filter all media from a comment
    public function media()
    {
        return $this->morphMany(Attachment::class, 'mediable', 'object_type', 'object_id');
    }
}
