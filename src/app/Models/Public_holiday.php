<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Public_holiday extends ModelExtended
{
    protected $fillable = ['id', 'name', 'years', 'workplace_id', 'ph_date', 'ph_hours'];
    protected $table = "public_holidays";
}
