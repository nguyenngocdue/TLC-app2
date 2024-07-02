<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_block extends ModelExtended
{
    protected $fillable = [
        "id", "name", "sql_string", "owner_id",
        "table_true_width", "max_h",
        "rotate_45_width", "rotate_45_height", "renderer_type", "chart_json", "has_pagination",
        "top_left_control", "top_center_control", "top_right_control",
        "bottom_left_control", "bottom_center_control", "bottom_right_control",
        "chart_type", "html_content",
    ];
}
