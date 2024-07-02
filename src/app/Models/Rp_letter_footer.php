<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_letter_footer extends ModelExtended
{
    protected $fillable = ["id", "name", "content", "owner_id"];
}
