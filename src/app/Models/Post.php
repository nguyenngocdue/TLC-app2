<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Post extends ModelExtended
{
    protected $fillable = ["name", "content", "owner_id"];

    protected $table = 'posts';

    public $eloquentParams = [];
}
