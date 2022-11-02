<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class User_discipline extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["name", "description", "def_assignee", "def_monitors", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'user_disciplines';
    protected $with = [
        'user',
    ];

    public $eloquentParams = [
        "user" => ['hasMany', User::class, 'discipline', 'id'],
    ];
    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
