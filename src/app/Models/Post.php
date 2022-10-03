<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["title", "content", "owner_id"];
    protected $primaryKey = 'id';
    protected $table = 'posts';


    public $eloquentParams = [
        "user" => ['belongsTo', User::class, 'owner_id'],
    ];
    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    // public function object()
    // {
    //     $p = $this->eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}();
    // }
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
