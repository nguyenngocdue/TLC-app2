<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Comment_category extends ModelExtended
{
    protected $fillable = [];
    protected $table = "fields";

    public $eloquentParams = [];
}
