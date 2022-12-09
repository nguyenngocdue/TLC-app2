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


    public $eloquentParams = [];

    public function commentable()
    {
        return $this->morphTo();
    }
}
