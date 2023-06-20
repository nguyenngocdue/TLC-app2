<?php

namespace App\View\Components\Formula;

use App\Models\Prod_order;
use App\Models\Wir_description;

class Wir_NameRendered
{
    public function __invoke($item)
    {
        $prodOrder = Prod_order::find($item['prod_order_id'])->toArray();
        $wirDesc = Wir_description::find($item['wir_description_id'])->toArray();
        $str = $prodOrder['name'] . '_' . $wirDesc['name'];
        return  $str;
    }
}
