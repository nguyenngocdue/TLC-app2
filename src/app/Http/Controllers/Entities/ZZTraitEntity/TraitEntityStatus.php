<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Helpers\Helper;


trait TraitEntityStatus
{
    private function setStatus($theRow, $data = null)
    {
        if (!isset($theRow['status'])) return false;
        $statusCurrent = $theRow['status'];
        $item = Helper::getItemModel($this->type, $theRow['id'] ?? $data->id);
        if ($statusCurrent === $item->status) return false;
        return $item->transitionTo($statusCurrent);
    }
}
