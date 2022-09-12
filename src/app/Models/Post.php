<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory,Searchable;
    protected $fillable = ["title", "content", "owner_id"];
    protected $primaryKey = 'id';
    protected $table = 'posts';


    public $eloquentParams = [
        "user" => ['belongsTo' , User::class, 'owner_id'],
        // "object" => ['morphTo']
    ];
    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1],$p[2]);
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
