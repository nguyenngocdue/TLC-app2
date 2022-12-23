<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Attachment_category extends ModelExtended
{
    protected $fillable = ["name", "description", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'fields';

    public $eloquentParams = [];
}
