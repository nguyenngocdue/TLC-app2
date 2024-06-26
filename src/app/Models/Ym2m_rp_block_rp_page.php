<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ym2m_rp_block_rp_page extends ModelExtended
{
    protected $fillable = [
        "id", "page_id", "block_id", "col_span", "background", "order_no", "owner_id"
    ];
}
