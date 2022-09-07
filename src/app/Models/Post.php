<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ["title", "content", "owner_id"];
    protected $primaryKey = 'id';
    protected $table = 'posts';


    public $eloquentParams = [
        "user" => ['belongsTo' , User::class],
        "object" => ['morphTo']
    ];
    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }
    public function object()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}();
    }
}
