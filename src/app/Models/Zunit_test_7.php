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
    protected $fillable = ['text', 'comment_1', 'comment_2', 'comment_3'];
    protected $table = "zunit_test_7s";

    public $eloquentParams = [];


    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable', 'commentable_type', 'commentable_id');
    }

    public function media()
    {
        return $this->morphMany(Attachment::class, 'mediable', 'object_type', 'object_id');
    }
}
