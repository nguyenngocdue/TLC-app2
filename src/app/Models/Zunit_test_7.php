<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Zunit_test_7 extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ['content', 'comment_1', 'comment_2', 'comment_3'];
    protected $table = "zunit_test_7s";
    public $menuTitle = "UT07 (Comments)";

    public $eloquentParams = [
        "comments" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
    ];

    public function comments()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }

    public function media()
    {
        return $this->morphMany(Attachment::class, 'mediable', 'object_type', 'object_id');
    }
}
