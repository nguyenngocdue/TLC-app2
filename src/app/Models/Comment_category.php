<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Comment_category extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = [];
    protected $table = "comment_categories";

    public $eloquentParams = [];
}
