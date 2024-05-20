<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait TraitEntityUpdateUserSettings
{
    public function updateUserSettings(Request $request)
    {
        if ($dataUserSettings = $request->input('userSettings')) {
            $type = Str::plural($this->type);
            $user = CurrentUser::get();
            $settings = $user->settings;
            $settings[$type][Constant::VIEW_EDIT]['value_filters_task'] = $dataUserSettings;
            $user->settings = $settings;
            $user->update();
            Toastr::success('User Settings Saved Successfully (#852)', 'Successfully');
        }
    }
}
