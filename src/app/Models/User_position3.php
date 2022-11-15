<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Notifications\Notifiable;


class User_position3 extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["name", "description", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'user_position3s';
    // protected $with = [
    //     'user',
    // ];

    public $eloquentParams = [
        "user" => ['hasMany', User::class, 'position_3', 'id'],
    ];
    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
