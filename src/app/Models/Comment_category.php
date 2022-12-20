<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Comment_category extends ModelExtended
{
    protected $fillable = [];
    protected $table = "comment_categories";

    public $eloquentParams = [];
}
