<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $fillable = ["url_folder", "url_thumbnail", "url_media", "filename", "owner_id"];
    protected $primaryKey = 'id';
    protected $table = 'medias';

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
