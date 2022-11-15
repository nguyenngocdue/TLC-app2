<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Notifications\Notifiable;


class Media_category extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["name", "description", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'media_categories';

    public $eloquentParams = [];
}
