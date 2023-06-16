<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

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
}
