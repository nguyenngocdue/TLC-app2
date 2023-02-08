<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Helpers\Helper;
use App\Utils\Support\Json\SuperProps;

trait TraitEntitySuperPropsFilter
{
    private function advanceFilter()
    {
        $blackList = ['attachment', 'comment', 'relationship_renderer', 'thumbnail', 'link_morph_to'];
        $supperProps = SuperProps::getFor($this->type);
        $propsFilters = array_filter($supperProps['props'], function ($item) use ($blackList) {
            if ($item['column_type'] === "static") {
                return false;
            } else {
                if (in_array($item['control'], $blackList)) return false;
                else {
                    if ($item['hidden_filter'] === 'true') return false;
                }
            }
            return true;
        });
        return $propsFilters;
    }
}
