<?php

namespace App\Http\Controllers\Entities;

use App\Helpers\Helper;


trait CreateEditControllerStatus
{
    private function setStatus($valueInput, $data = null)
    {
        if (!isset($valueInput['status'])) return false;
        $statusCurrent = $valueInput['status'];
        $item = Helper::getItemModel($this->type, $valueInput['id'] ?? $data->id);
        if ($statusCurrent === $item->status) return false;
        return $item->transitionTo($statusCurrent);
    }
}
