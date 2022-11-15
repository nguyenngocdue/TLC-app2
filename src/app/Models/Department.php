<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Notifications\Notifiable;


class Department extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["name", "description", "head_of_department", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'departments';
    // protected $with = [
    // 'user',
    // 'Users_Count',
    // ];
    public $eloquentParams = [
        "user" => ['belongsTo', User::class, 'head_of_department'],
        "Users_Count" => ['hasMany', User::class, 'department'],
    ];
    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function Users_Count()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
