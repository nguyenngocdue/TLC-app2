<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Controls\TraitMorphTo;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitViewAllFunctions
{
    use TraitEntityAdvancedFilter;
    use TraitSupportPermissionGate;
    use TraitMorphTo;

    private function getUserSettingsViewAll()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $perPage = $settings[$type][Constant::VIEW_ALL]['per_page'] ?? 15;
        $columnLimit = $settings[$type][Constant::VIEW_ALL]['columns'] ?? null;
        $advancedFilter = $settings[$type][Constant::VIEW_ALL]['advanced_filters'] ?? null;
        $basicFilter = $settings[$type][Constant::VIEW_ALL]['basic_filters'] ?? [];
        $chooseBasicFilter = $settings[$type][Constant::VIEW_ALL]['choose_basic_filters'] ?? null;
        $currentFilter = $settings[$type][Constant::VIEW_ALL]['current_filter'] ?? null;
        $refreshPage = $settings[$type][Constant::VIEW_ALL]['refresh_page'] ?? null;
        $optionPrint = $settings[$type][Constant::VIEW_ALL]['option_print_layout'] ?? null;
        $viewAllMode = $settings[$type][Constant::VIEW_ALL]['view_all_mode'] ?? null;
        return [$perPage, $columnLimit, $advancedFilter, $currentFilter, $refreshPage, $basicFilter, $chooseBasicFilter, $optionPrint, $viewAllMode];
    }

    private function getUserSettingsViewAllCalendar()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $filterViewAllCalendar = $settings[$type][Constant::VIEW_ALL]['calendar'] ?? null;
        $viewAllCalendarShowAllChildren = $settings[$type][Constant::VIEW_ALL]['calendar_options']['show_all_children'] ?? null;
        $viewAllMode = $settings[$type][Constant::VIEW_ALL]['view_all_mode'] ?? null;
        return [$viewAllMode, $filterViewAllCalendar, $viewAllCalendarShowAllChildren];
    }
    private function getUserSettingsViewOrgChart()
    {
        $settings = CurrentUser::getSettings();
        return $settings[Constant::VIEW_ORG_CHART]['show_options'] ?? [];
    }

    // private function getUserSettingsViewAllMatrix()
    // {
    //     $type = Str::plural($this->type);
    //     $settings = CurrentUser::getSettings();
    //     $viewportDate = $settings[$type][Constant::VIEW_ALL]['matrix']['viewport_date'] ?? null;
    //     $viewportMode = $settings[$type][Constant::VIEW_ALL]['matrix']['viewport_mode'] ?? null;
    //     return [$viewportDate, $viewportMode];
    // }

    private function getEagerLoadParams($eloquentParams)
    {
        $eagerLoadParams = array_keys(array_filter($eloquentParams, fn($item) => in_array($item[0], ['belongsTo', 'hasMany', 'morphMany', 'morphTo'])));
        return $eagerLoadParams;
    }

    private function getDataSourceForViewCalendar($filter)
    {
        $startDate = isset($filter['start_date']) ? $filter['start_date'] : Carbon::now()->startOfYear()->toDateString();
        $endDate = isset($filter['end_date']) ? $filter['end_date'] : Carbon::now()->endOfYear()->toDateString();
        $ownerId = isset($filter['owner_id']) ? $filter['owner_id'] : [CurrentUser::id()];

        return ($this->typeModel)::query()
            ->whereIn('owner_id', $ownerId)
            ->whereDate('week', '>=', $startDate)
            ->whereDate('week', '<=',  $endDate);
        // if ($filter) {
        //     if(!isset($filter['start_date'])) {
        //         $filter['start_date'] =  Carbon::now();
        //     }
        //     return ($this->typeModel)::query()
        //         ->whereIn('owner_id', $filter['owner_id'])
        //         ->whereDate('week', '>=', $filter['start_date'])
        //         ->whereDate('week', '<=',  $filter['end_date']);
        // }
        // $startDate = Carbon::now()->startOfYear()->toDateString();
        // $endDate = Carbon::now()->endOfYear()->toDateString();
        // return ($this->typeModel)::whereIn('owner_id', [CurrentUser::id()])->whereDate('week', '>=', $startDate)
        //     ->whereDate('week', '<=',  $endDate);
    }

    public function getDataSource($advanceFilters = null, $trash = false)
    {
        $propsFilters = $this->advanceFilter();
        $advanceFilters = $this->groupFilter($advanceFilters, $propsFilters);
        $model = $this->typeModel;
        $instance = App::make($model);
        $instance = $instance->search(); //<< has to be here even scout is database
        $eloquentParams = $model::$eloquentParams;
        $eagerLoadParams = $this->getEagerLoadParams($eloquentParams);
        if (!CurrentUser::isAdmin()) {
            $isUseTree = $this->isUseTree($this->type);
            if ($isUseTree) {
                $ids = $this->getListOwnerIds(CurrentUser::get());
                $result = $instance
                    ->query(function ($q) use ($ids, $advanceFilters, $propsFilters, $eagerLoadParams, $trash) {
                        if ($trash) {
                            $q->onlyTrashed();
                        }
                        $this->handleWhereIn($q, $ids);
                        $this->queryAdvancedFilter($q, $advanceFilters, $propsFilters);
                        return $q
                            ->with($eagerLoadParams)
                            ->orderBy('updated_at', 'desc');
                    });
                return $result;
            }
        }
        $result = $instance
            ->query(function ($q) use ($advanceFilters, $propsFilters, $eagerLoadParams, $trash) {
                if ($trash) {
                    $q->onlyTrashed();
                }
                $this->queryAdvancedFilter($q, $advanceFilters, $propsFilters);
                return $q
                    ->with($eagerLoadParams)
                    ->orderBy('updated_at', 'desc');
            });
        return $result;
    }
    private function handleWhereIn(&$model, $ids)
    {
        $currentUserRoleSet = CurrentUser::getRoleSet();
        if ($this->type == "user_position" && $currentUserRoleSet != "hr_manager") {
            $positions = $this->getPositionsEntityUserPositionOfCurrentUser();
            $model->whereIn('name', array_unique($positions));
        } else {
            $model->whereIn('owner_id', $ids);
        }
    }
}
