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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function object()
    {
        return $this->morphTo();
    }
}
