<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_page extends ModelExtended
{
    protected $fillable = [
        "id", "title", "report_id", "letter_head_id", "letter_footer_id",
        "is_landscape", "width", "height", "background",
        "is_stackable_letter_head", "is_full_width", "order_no", "owner_id"
    ];
}
