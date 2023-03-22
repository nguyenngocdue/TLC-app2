<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityAdvancedFilter;
use App\Models\User;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperProps;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UpdateUserSettings extends Controller
{
    use TraitEntityAdvancedFilter;
    private function updatePerPage($request, $settings)
    {
        $perPage = $request->input('per_page');
        $type = $request->input("_entity");
        $settings[$type][Constant::VIEW_ALL]['per_page'] = $perPage;
        return $settings;
    }

    private function updateGear($request, $settings)
    {
        [$type, $toBeInserted] = $this->formatRequestValue($request);
        $settings[$type][Constant::VIEW_ALL]['columns'] = $toBeInserted;
        return $settings;
    }
    private function updateFilter($request, $settings)
    {
        [$type, $valueRequest] = $this->formatRequestValue($request);
        $settings[$type][Constant::VIEW_ALL]['advanced_filters'] = $valueRequest;
        return $settings;
    }
    private function clearFilter($request, $settings)
    {
        [$type,] = $this->formatRequestValue($request);
        unset($settings[$type]);
        return $settings;
    }
    private function formatRequestValue($request)
    {
        $regexDate = '/[0123][0-9][\/][01][0-9][\/][0-9]{4} - [0123][0-9][\/][01][0-9][\/][0-9]{4}/m';
        $regexTime = '/^(((([0-1][0-9])|(2[0-3])):?[0-5][0-9]:?[0-5][0-9]+)) - (((([0-1][0-9])|(2[0-3])):?[0-5][0-9]:?[0-5][0-9]+$))/m';
        $regexMonth = '/([0][1-9]|1[0-2])[\/][0-9]{4} - [01][0-9][\/][0-9]{4}/m';
        $regexWeek = '/[Ww]([0-4][0-9]|5[0-3])[\/][0-9]{4} - [Ww]([0-4][0-9]|5[0-3])[\/][0-9]{4}/m';
        $regexQuarter = '/[Qq][1-4][\/][0-9]{4} - [Qq][1-4][\/][0-9]{4}/m';
        $regexYear = '/[0-9]{4} - [0-9]{4}/m';
        $all = $request->all();
        $type = $all['_entity'];
        $superProps = $this->advanceFilter($type);
        $result = [];
        foreach ($superProps as $key => $value) {
            $result[substr($key, 1)] = $value['control'];
        }
        $arrayFlip = ['_method', '_token', '_entity', 'action'];
        foreach ($all as $key => $value) {
            if (isset($result[$key]))
                switch ($result[$key]) {
                    case 'picker_time':
                        $arrayFlip = $this->matchRegex($regexTime, $key, $value, $arrayFlip);
                        break;
                    case 'picker_month':
                        $arrayFlip = $this->matchRegex($regexMonth, $key, $value, $arrayFlip);
                        break;
                    case 'picker_week':
                        $arrayFlip = $this->matchRegex($regexWeek, $key, $value, $arrayFlip);
                        break;
                    case 'picker_quarter':
                        $arrayFlip = $this->matchRegex($regexQuarter, $key, $value, $arrayFlip);
                        break;
                    case 'picker_year':
                        $arrayFlip = $this->matchRegex($regexYear, $key, $value, $arrayFlip);
                        break;
                    case 'picker_datetime':
                    case 'picker_date':
                        $arrayFlip = $this->matchRegex($regexDate, $key, $value, $arrayFlip);
                        break;
                    default:
                        break;
                }
        }
        $toBeInserted = array_diff_key($all, array_flip($arrayFlip));
        return [$type, $toBeInserted];
    }
    private function matchRegex($regex, $key, $value, $arrayFlip)
    {
        if (preg_match_all($regex, $value, $matches, PREG_SET_ORDER, 0) == 0 && $value != null) {
            $arrayFlip[] = $key;
            Session::flash($key, '
            Enter the wrong format');
        }
        return $arrayFlip;
    }

    private function resetParamsReport($request, $settings)
    {
        $inputValue = $request->all();
        $entity = $request->input("_entity");
        $typeReport = strtolower($request->input("type_report"));
        $settingUser = CurrentUser::getSettings();
        $modeNames = $inputValue['mode_name'];
        if (isset($settingUser[$entity][$typeReport][$modeNames])) {
            $paramsReset = $settingUser[$entity][$typeReport][$modeNames];
            array_walk($paramsReset, function ($value, $key) use (&$paramsReset) {
                $paramsReset[$key] = null;
            });
            $settings[$entity][$typeReport][$modeNames] = $paramsReset;
        }
        return $settings;
    }


    private function updateReport($request, $settings)
    {
        $inputValue = $request->all();
        if (isset($inputValue['mode_name'])) {
            return $this->resetParamsReport($request, $settings);
        }
        $modeName = $inputValue['mode_option'];
        // Check case: select mode alternatively 
        $index = array_search($modeName, array_values($inputValue));
        if (empty(array_slice($inputValue, $index + 1, count($inputValue) - $index))) return $settings;
        return $settings;
    }

    private function updatePerPageRegister($request, $settings)
    {
        // dd(132);
        $entity = $request->input("_entity");
        $typeReport = strtolower($request->input("type_report"));
        $perPage = $request->input('page_limit');
        $settings[$entity][$typeReport]['page_limit'] = $perPage;
        return $settings;
    }



    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // dd($request);
        $action = $request->input('action');
        $user = User::find(Auth::id());
        $settings = $user->settings;
        switch ($action) {
            case 'updatePerPage':
                $settings = $this->updatePerPage($request, $settings);
                break;
            case 'updateGear':
                $settings = $this->updateGear($request, $settings);
                break;
            case 'updateAdvanceFilter':
                $settings = $this->updateFilter($request, $settings);
                break;
            case 'clearAdvanceFilter':
                $settings = $this->clearFilter($request, $settings);
                break;
                //report 
            case 'updateReportRegisters':
                $settings = $this->updateReport($request, $settings);
                break;
            case 'updateReportReports':
                $settings = $this->updateReport($request, $settings);
                break;
            case 'updateReportDocuments':
                $settings = $this->updateReport($request, $settings);
                break;
            case 'updatePerPageRegisters':
                $settings = $this->updatePerPageRegister($request, $settings);
                break;
            case 'updatePerPageReports':
                $settings = $this->updatePerPageRegister($request, $settings);
                break;
            case 'updatePerPageDocuments':
                $settings = $this->updatePerPageRegister($request, $settings);
                break;
            default:
                Log::error("Unknown action $action");
                break;
        }
        $user->settings = $settings;
        $user->update();
        Toastr::success('User Settings Saved Successfully', 'Successfully');
        return redirect()->back();
    }
}
