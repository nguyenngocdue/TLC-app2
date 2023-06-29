<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Hse_insp_group;
use App\Models\Qaqc_insp_group;

trait TraitGetGroupChkSht
{
    private function getGroups($lines)
    {
        $groupColumn = '';
        $groupNames = [];
        switch ($this->type) {
            case 'qaqc_insp_chklst_shts':
                $groupColumn =  'qaqc_insp_group_id';
                $groupIds = $lines->pluck($groupColumn)->unique();
                $groupNames = Qaqc_insp_group::whereIn('id', $groupIds)->get()->pluck('name', 'id');
                break;
            case 'hse_insp_chklst_shts':
                $groupColumn = 'hse_insp_group_id';
                $groupIds = $lines->pluck($groupColumn)->unique();
                $groupNames = Hse_insp_group::whereIn('id', $groupIds)->get()->pluck('name', 'id');
                break;
            default:
                dump("Error: Unknown group column of type $this->type");
                break;
        }
        return [$groupColumn, $groupNames];
    }
}