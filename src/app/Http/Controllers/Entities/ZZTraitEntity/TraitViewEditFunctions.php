<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\User;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Str;

trait TraitViewEditFunctions
{
    private function getUserSettingsViewEditCalendar()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $valueFiltersTask = $settings[$type][Constant::VIEW_EDIT]['value_filters_task'] ?? null;
        return [$valueFiltersTask];
    }

    private function getSheetOwner($timesheetableType, $timesheetableId)
    {
        $uid = ($timesheetableType)::findFromCache($timesheetableId)->owner_id ?? CurrentUser::id();
        return User::findFromCache($uid);
    }
}
