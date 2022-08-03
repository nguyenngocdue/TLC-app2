<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ["url_folder", "url_thumbnail", "url_media", "filename", "user_id"];
    protected $primaryKey = 'id';
    protected $table = 'medias';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function object()
    {
        return $this->morphTo();
    }
}
