<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_column extends ModelExtended
{
    protected $fillable = [
        "table_id", "parent_id", "is_active", "data_index",
        "order_no", "name", "width", "cell_div_class_agg_footer", "cell_div_class",
        "cell_class", "icon", "icon_position", "row_cell_div_class", "row_cell_class",
        "row_icon", "row_icon_position", "row_href_fn", "row_renderer", "agg_footer",
        "owner_id"
    ];
}
