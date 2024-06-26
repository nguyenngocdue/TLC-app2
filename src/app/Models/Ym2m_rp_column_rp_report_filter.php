<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ym2m_rp_column_rp_report_filter extends ModelExtended
{
    protected $fillable = [
        "id", "report_id", "column_id", "col_span", "bw_list_ids", "black_or_white",
        "is_required", "default_value", "has_listen_to",
        "allow_clear", "is_multiple", "control_type", "owner_id"
    ];
}
