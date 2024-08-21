<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityAdvancedFilter;
use App\Models\Rp_report;
use App\Models\User;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\Report;
use App\View\Components\Reports2\TransferUserSettingReport2;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
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
    private function updateGlobal($request, $settings)
    {
        $supported = ['theme-bg', 'theme-text', 'selected-project-id'];
        foreach ($supported as $key) {
            if ($request->has($key)) {
                $themeBg = $request->input($key);
                $settings[Constant::GLOBAL][$key] = $themeBg;
            }
        }
        return $settings;
    }
    private function updateShowOptionsOrgChart($request, $settings)
    {
        $showOptions = $request->input('show_options');
        $settings[Constant::VIEW_ORG_CHART]['show_options'] = $showOptions;
        return $settings;
    }
    private function getStartAndEndFilterByYear($year)
    {
        $year = $year ?: Carbon::now()->year;
        return [$year . '-' . '01-01', $year . '-' . '12-31', $year];

        // if ($year) {
        //     return $this->getDurationForFilterCalendar($year);
        // } else {
        //     $year = Carbon::now()->year;
        //     return $this->getDurationForFilterCalendar($year);
        // }
    }
    // private function getDurationForFilterCalendar($year)
    // {
    //     // $start = DateTimeConcern::getWeekOfYear(($year) . '-' . '01' . '-' . '01');
    //     // $end = DateTimeConcern::getWeekOfYear($year . '-' . '12' . '-' . '31');
    //     // return DateTimeConcern::formatWeekYear(1, $year, $end, $year);
    //     return [$year . '-' . '01-01', $year . '-' . '12-31', $year];
    // }

    private function updateViewAllMatrix($request, &$settings)
    {
        $type = $request->input("_entity");
        $toBeSaved = $request->except(["_token", "_method", "_entity", "action"]);
        if (isset($toBeSaved['page'])) {
            if (isset($toBeSaved['key'])) {
                $key = $toBeSaved['key'];
                $settings[$type][Constant::VIEW_ALL]['matrix'][$key]["page"] = $toBeSaved['page'];
            } else {
                $settings[$type][Constant::VIEW_ALL]['matrix']["page"] = $toBeSaved['page'];
            }
        } else {
            $settings[$type][Constant::VIEW_ALL]['matrix'] = $toBeSaved;
        }
        return $settings;
    }

    private function updateDashboardMatrix($request, &$settings)
    {
        $type = $request->input("_entity");
        $toBeSaved = $request->except(["_token", "_method", "_entity", "action"]);
        $settings[$type][Constant::VIEW_ALL]['dashboard_matrix'] = $toBeSaved;
        return $settings;
    }

    private function updateViewAllCalendar($request, &$settings)
    {
        $type = $request->input("_entity");
        $year = $request->input("year");
        $ownerId = $request->input("owner_id");
        $settingCalendar = $settings[$type][Constant::VIEW_ALL]['calendar'] ?? null;
        if (!$ownerId) {
            $ownerId = isset($settingCalendar['owner_id']) ? $settingCalendar['owner_id'] : [CurrentUser::id()];
        }
        if (!$year) {
            $year = isset($settingCalendar['year']) ? $settingCalendar['year'] : null;
        }
        [$start, $end, $year] = $this->getStartAndEndFilterByYear($year);
        $data = [
            'start_date' => $start,
            'end_date' => $end,
            'owner_id' => $ownerId,
            'year' => $year,
        ];
        $settings[$type][Constant::VIEW_ALL]['calendar'] = $data;
        return $settings;
    }
    private function updateViewAllMode($request, $settings)
    {
        $type = $request->input("_entity");
        $value = $request->input("view_type");
        if ($value == 'calendar') {
            $this->updateViewAllCalendar($request, $settings);
        }
        $settings[$type][Constant::VIEW_ALL]['view_all_mode'] = $value;
        return $settings;
    }

    private function updateOptionPrintLayout($request, $settings)
    {
        $value = $request->input('option_print_layout');
        if (in_array($value, ['portrait', 'landscape'])) {
            $type = $request->input("_entity");
            $settings[$type][Constant::VIEW_ALL]['option_print_layout'] = $value;
        }
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
        $settings[$type][Constant::VIEW_ALL]['current_filter'] = 'advance_filter';
        $settings[$type][Constant::VIEW_ALL]['choose_basic_filters'] = null;
        $settings[$type][Constant::VIEW_ALL]['advanced_filters'] = $valueRequest;
        return $settings;
    }
    private function updateValueAdvanceFilter($request, $settings)
    {
        [$type, $valueRequest] = $this->formatRequestValue($request);
        $valueColumnNeedChange = array_keys($valueRequest) ?? [];
        $valueAdvanceFilter = $settings[$type][Constant::VIEW_ALL]['advanced_filters'] ?? [];
        foreach ($valueColumnNeedChange as $key) {
            $valueAdvanceFilter[$key] = $valueRequest[$key];
        }
        $settings[$type][Constant::VIEW_ALL]['current_filter'] = 'advance_filter';
        $settings[$type][Constant::VIEW_ALL]['choose_basic_filters'] = null;
        $settings[$type][Constant::VIEW_ALL]['advanced_filters'] = $valueAdvanceFilter;
        return $settings;
    }
    private function updateBasicFilter($request, $settings, $filter = 'basic_filter')
    {
        $chooseBasicFilter = $request->input('choose_basic_filter');
        [$type,] = $this->formatRequestValue($request);
        if ($chooseBasicFilter || !empty($chooseBasicFilter)) {
            $settings[$type][Constant::VIEW_ALL]['current_filter'] = $filter;
            $settings[$type][Constant::VIEW_ALL]['choose_basic_filters'] = $chooseBasicFilter;
            $valueRequest = $settings[$type][Constant::VIEW_ALL]['basic_filters'][$chooseBasicFilter];
            $settings[$type][Constant::VIEW_ALL]['advanced_filters'] = $valueRequest;
            return $settings;
        }
        $settings[$type][Constant::VIEW_ALL]['advanced_filters'] = [];
        return $settings;
    }
    private function saveBasicFilter($request, $settings)
    {
        $nameFilter = $request->input('basic_filter');
        [$type, $valueRequest] = $this->formatRequestValue($request);
        $settings[$type][Constant::VIEW_ALL]['current_filter'] = 'advance_filter';
        $settings[$type][Constant::VIEW_ALL]['choose_basic_filters'] = $nameFilter;
        $settings[$type][Constant::VIEW_ALL]['basic_filters'][$nameFilter] = $valueRequest;
        $settings[$type][Constant::VIEW_ALL]['advanced_filters'] = $valueRequest;
        return $settings;
    }
    private function updateRefreshPage($request, $settings)
    {
        [$type,] = $this->formatRequestValue($request);
        $value = $settings[$type][Constant::VIEW_ALL]['refresh_page'] ?? null;
        $settings[$type][Constant::VIEW_ALL]['refresh_page'] = !$value;
        return $settings;
    }
    private function deletedBasicFilter($request, $settings, $nameFilterInput = 'choose_basic_filter')
    {
        $nameFilter = $request->input($nameFilterInput);
        [$type,] = $this->formatRequestValue($request);
        unset($settings[$type][Constant::VIEW_ALL]['choose_basic_filters']);
        unset($settings[$type][Constant::VIEW_ALL]['basic_filters'][$nameFilter]);
        $settings[$type][Constant::VIEW_ALL]['advanced_filters'] = [];
        return $settings;
    }
    private function clearFilter($request, $settings)
    {
        [$type,] = $this->formatRequestValue($request);
        unset($settings[$type][Constant::VIEW_ALL]['choose_basic_filters']);
        unset($settings[$type][Constant::VIEW_ALL]['advanced_filters']);
        $settings[$type][Constant::VIEW_ALL]['current_filter'] = 'advance_filter';
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
        $arrayFlip = ['_method', '_token', '_entity', 'action', 'basic_filter', 'choose_basic_filter'];
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
        $modeOption = $inputValue['mode_option'];
        if (isset($settingUser[$entity][$typeReport][$modeOption])) {
            $paramsReset = $settingUser[$entity][$typeReport][$modeOption];
            array_walk($paramsReset, function ($value, $key) use (&$paramsReset) {
                $paramsReset[$key] = null;
            });
            $settings[$entity][$typeReport][$modeOption] = $paramsReset;
        }
        return $settings;
    }

    private function updateParamsReportForFirstSubmit($inputValue, $settings)
    {
        $modeName = $inputValue['mode_option'];
        // Check case: select mode alternatively 
        $index = array_search($modeName, array_values($inputValue));
        if (empty(array_slice($inputValue, $index + 1, count($inputValue) - $index))) return $settings;
        // Create date to update params into user_setting
        unset($inputValue['mode_option']);
        $entity = $inputValue["_entity"];
        $typeReport = strtolower($inputValue["type_report"]);
        $indexBreak = array_search("type_report", array_keys($inputValue));
        $parameter = array_slice($inputValue, $indexBreak + 1, count($inputValue) - $indexBreak);

        if (isset($inputValue['children_mode']) && $inputValue['children_mode'] !== 'not_children') {
            $modeSelect = $inputValue['children_mode'];
            $settings[$entity][$typeReport][$modeName]['children_mode'] = $inputValue['children_mode'];
            $settings[$entity][$typeReport][$modeName][$modeSelect] = $parameter;
        } else {
            $settingUser = CurrentUser::getSettings();
            if (isset($settingUser[$entity][$typeReport][$modeName]) && isset($parameter['forward_to_mode'])) {
                $parameter['forward_to_mode'] = $settingUser[$entity][$typeReport][$modeName]['forward_to_mode'];
            }
            $settings[$entity][$typeReport][$modeName] = $parameter;
        }
        // dd($settings);
        return $settings;
    }

    private function updateOptionPrintReport($request, $settings)
    {
        $inputValue = $request->all();
        $entity = $request->input("_entity");
        $typeReport = strtolower($request->input("type_report"));
        $settingUser = CurrentUser::getSettings();
        $modeOption = $inputValue['mode_option'];
        if (isset($settingUser[$entity][$typeReport][$modeOption])) {
            $paramsReset = $settingUser[$entity][$typeReport][$modeOption];
            $paramsReset['optionPrintLayout'] = $inputValue['optionPrintLayout'];
            $settings[$entity][$typeReport][$modeOption] = $paramsReset;
        } else {
            // dd($inputValue);
            $settings = $this->updateParamsReportForFirstSubmit($inputValue, $settings);
        }
        return $settings;
    }

    private function switchParamsReport($request, $settings)
    {
        $inputValue = $request->all();
        $entity = $request->input("_entity");
        $typeReport = strtolower($request->input("type_report"));
        $modeOption = $inputValue['mode_option'];
        if (isset($inputValue['children_mode'])) {
            $modeSelect = $inputValue['children_mode'];
            $settings[$entity][$typeReport][$modeOption]['children_mode'] = $modeSelect;
        }
        if (isset($inputValue['forward_to_mode'])) {
            $settingUser = CurrentUser::getSettings();
            if (!isset($settingUser[$entity])) {
                $settings[$entity][$typeReport][$modeOption]['forward_to_mode'] = $inputValue['forward_to_mode'];
            }
            if (isset($settingUser[$entity][$typeReport][$modeOption])) {
                $paramsReset = $settingUser[$entity][$typeReport][$modeOption];
                $paramsReset['forward_to_mode'] = $inputValue['forward_to_mode'];
                $settings[$entity][$typeReport][$modeOption] = $paramsReset;
            }
        }
        // dd($settings);
        return $settings;
    }

    private function updateReport($request, $settings)
    {
        $inputValue = $request->all();
        if (isset($inputValue['form_type']) && $inputValue['form_type'] === "resetParamsReport") {
            return $this->resetParamsReport($request, $settings);
        }
        if (isset($inputValue['form_type']) && $inputValue['form_type'] === "updateOptionPrintReport") {
            return $this->updateOptionPrintReport($request, $settings);
        }
        $settings = $this->updateParamsReportForFirstSubmit($inputValue, $settings);
        return $settings;
    }

    private function getFilterReport2($dataInput, $reset = false)
    {
        $params = [];
        foreach ($dataInput as $key => $value) {
            if (!in_array($key, ['_token', 'action', 'entity_type', 'entity_type2', 'report_id', 'form_type'])) {
                $params[$key] = $reset ? null : $value;
            }
        };
        return $params;
    }


    private function updateReport2($request, $settings)
    {
        $inputValue = $request->all();
        $entityType = $inputValue['entity_type'];
        $entityType2 = $inputValue['entity_type2'];
        $rpId = $inputValue['report_id'];
        $filters = $this->getFilterReport2($inputValue);
    
        $rpFilterLinks = Rp_report::find($rpId)->getDeep()->getRpFilterLinks;
        $storedFilterKey = Report::getStoredFilterKey($rpId,$rpFilterLinks);
        // dd($storedFilterKey);
    
        $keys = [$entityType, $entityType2, $storedFilterKey];
        $paramToUpdate = [];
        if (Report::checkKeysExist($settings, $keys)) {
            $paramsInUser = &$settings[$entityType][$entityType2][$storedFilterKey];
            foreach ($filters as $key => $value) {
                $paramsInUser[$key] = (isset($inputValue['form_type']) && $inputValue['form_type'] === "resetParamsReport2") ? null : $value;
            }
            $paramToUpdate = $paramsInUser;
        } else {
            $paramToUpdate = $filters;
        }
        $settings[$entityType][$entityType2][$storedFilterKey] = $paramToUpdate;
        return $settings;
    }
    
    private function updatePerPageReports($request, $settings)
    {
        $entity = $request->input("_entity");
        $typeReport = strtolower($request->input("type_report"));
        $perPage = $request->input('per_page');
        $settings[$entity][$typeReport]['per_page'] = $perPage;
        return $settings;
    }

    public function __invoke(Request $request, $redirectTo = null)
    {
        $action = $request->input('action');
        $user = User::find(Auth::id());
        $settings = $user->settings;
        switch ($action) {
            case 'updateGlobal':
                $settings = $this->updateGlobal($request, $settings);
                break;
            case 'updateShowOptionsOrgChart':
                $settings = $this->updateShowOptionsOrgChart($request, $settings);
                break;
            case 'updateViewAllMode':
                $settings = $this->updateViewAllMode($request, $settings);
                break;
            case 'updateViewAllCalendar':
                $settings = $this->updateViewAllCalendar($request, $settings);
                break;
            case 'updateViewAllMatrix':
                $settings = $this->updateViewAllMatrix($request, $settings);
                break;
            case 'updateDashboardMatrix':
                $settings = $this->updateDashboardMatrix($request, $settings);
                break;
            case 'updatePerPage':
                $settings = $this->updatePerPage($request, $settings);
                break;
            case 'updateGear':
                $settings = $this->updateGear($request, $settings);
                break;
            case 'updateRefreshPage':
                $settings = $this->updateRefreshPage($request, $settings);
                break;
            case 'saveBasicFilter':
                $settings = $this->saveBasicFilter($request, $settings);
                break;
            case 'deletedBasicFilter':
                $settings = $this->deletedBasicFilter($request, $settings);
                break;
            case 'deletedBasicFilter2':
                $settings = $this->deletedBasicFilter($request, $settings, 'basic_filter_delete');
                break;
            case 'updateBasicFilter':
                $settings = $this->updateBasicFilter($request, $settings);
                break;
            case 'updateBasicFilter2':
                $settings = $this->updateBasicFilter($request, $settings, 'advance_filter');
                break;
            case 'updateAdvanceFilter':
                $settings = $this->updateFilter($request, $settings);
                break;
            case 'clearAdvanceFilter':
                $settings = $this->clearFilter($request, $settings);
                break;
            case 'updateOptionPrintLayout':
                $settings = $this->updateOptionPrintLayout($request, $settings);
                break;
                //report 
            case 'updateReportRegisters':
                $settings = $this->updateReport($request, $settings);
                break;
            case 'updateReportPivot-reports':
                $settings = $this->updateReport($request, $settings);
                break;
            case 'updateReportReports':
                $settings = $this->updateReport($request, $settings);
                break;
            case 'updateReportDocuments':
                $settings = $this->updateReport($request, $settings);
                break;
            case 'updateReport2':
                $settings = $this->updateReport2($request, $settings);
                break;
            case 'updatePerPageRegisters':
                $settings = $this->updatePerPageReports($request, $settings);
                break;
            case 'updatePerPageReports':
                $settings = $this->updatePerPageReports($request, $settings);
                break;
            case 'updatePerPagePivot-reports':
                $settings = $this->updatePerPageReports($request, $settings);
                break;
            case 'switchParamsReport':
                $settings = $this->switchParamsReport($request, $settings);
                break;
            case 'updatePerPageDocuments':
                $settings = $this->updatePerPageReports($request, $settings);
                break;
            case 'updateValueAdvanceFilter':
                $settings = $this->updateValueAdvanceFilter($request, $settings);
                break;
            case 'resetAllSettings':
                $settings = [];
                break;
            default:
                Log::error("Unknown action $action");
                break;
        }
        $user->settings = $settings;
        $user->update();
        toastr()->success('User Settings Saved Successfully', 'Successfully');
        if (is_null($redirectTo)) return redirect()->back();
        return redirect($redirectTo);
    }
}
