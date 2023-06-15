<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\Qaqc_insp_chklst_line;
use App\Models\Qaqc_mir;
use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use App\View\Components\Controls\TraitMorphTo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitViewAllFunctions
{
    use TraitEntityAdvancedFilter;
    use TraitSupportPermissionGate;
    use TraitMorphTo;

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $perPage = $settings[$type][Constant::VIEW_ALL]['per_page'] ?? 20;
        $columnLimit = $settings[$type][Constant::VIEW_ALL]['columns'] ?? null;
        $advancedFilter = $settings[$type][Constant::VIEW_ALL]['advanced_filters'] ?? null;
        $basicFilter = $settings[$type][Constant::VIEW_ALL]['basic_filters'] ?? [];
        $chooseBasicFilter = $settings[$type][Constant::VIEW_ALL]['choose_basic_filters'] ?? null;
        $currentFilter = $settings[$type][Constant::VIEW_ALL]['current_filter'] ?? null;
        $refreshPage = $settings[$type][Constant::VIEW_ALL]['refresh_page'] ?? null;
        $optionPrint = $settings[$type][Constant::VIEW_ALL]['option_print_layout'] ?? null;
        $filterViewAllCalendar = $settings[$type][Constant::VIEW_ALL]['calendar'] ?? null;
        $viewAllMode = $settings[$type][Constant::VIEW_ALL]['view_all_mode'] ?? null;
        $viewAllCalendarShowAllChildren = $settings[$type][Constant::VIEW_ALL]['calendar']['show_all_children'] ?? null;
        return [$perPage, $columnLimit, $advancedFilter, $currentFilter, $refreshPage, $basicFilter, $chooseBasicFilter, $optionPrint, $viewAllMode, $filterViewAllCalendar, $viewAllCalendarShowAllChildren];
    }

    private function getEagerLoadParams($eloquentParams)
    {
        $eagerLoadParams = array_keys(array_filter($eloquentParams, fn ($item) => in_array($item[0], ['belongsTo', 'hasMany', 'morphMany', 'morphTo'])));
        // dump($eagerLoadParams);

        // $x = $this->getAllTypeMorphMany();
        // dump($x);
        // $morphManyArray = array_map(fn ($item) => [$item['id'] => [$item['fn']]], $x);
        // dump($morphManyArray);

        // $paramsOfMorph = array_filter($eloquentParams, fn ($item) => in_array($item[0], ['morphTo']));
        // // dump($paramsOfMorph);
        // $keys = array_keys($paramsOfMorph);
        // dump($keys);
        // foreach ($keys as $key) {
        //     $eagerLoadParams[$key] = function (MorphTo $morphTo) use ($morphManyArray) {
        //         $morphTo->morphWith($morphManyArray);
        //     };
        // }

        // $eagerLoadParams['getParent'] = function (MorphTo $morphTo) {
        //     $morphTo->morphWith([
        //         Qaqc_wir::class => ['getNcrs'],
        //         Qaqc_mir::class => ['getNcrs'],
        //         Qaqc_insp_chklst_line::class => ['getNcrs'],
        //     ]);
        // };
        // dump($eagerLoadParams);

        return $eagerLoadParams;
    }
    private function getDataSourceForViewCalendar($filter)
    {
        return ($this->typeModel)::whereIn('owner_id', $filter['owner_id'])->whereDate('week', '>=', $filter['start_date'])
            ->whereDate('week', '<=',  $filter['end_date']);
    }

    private function getDataSource($advanceFilters = null, $trash = false)
    {
        $propsFilters = $this->advanceFilter();
        $advanceFilters = $this->distributeFilter($advanceFilters, $propsFilters);
        $model = $this->typeModel;
        $search = request('search');
        $instance = App::make($model);
        $eloquentParams = $instance->eloquentParams;
        $eagerLoadParams = $this->getEagerLoadParams($eloquentParams);

        $relation = $instance->search($search);
        if (!CurrentUser::isAdmin()) {
            $isUseTree = $this->isUseTree($this->type);
            if ($isUseTree) {
                $ids = $this->getListOwnerIds(auth()->user());
                $result = $relation
                    ->query(function ($q) use ($ids, $advanceFilters, $propsFilters, $eagerLoadParams, $trash) {
                        if ($trash) {
                            $q->onlyTrashed();
                        }
                        $q->whereIn('owner_id', $ids);
                        $this->queryAdvancedFilter($q, $advanceFilters, $propsFilters);
                        return $q
                            ->with($eagerLoadParams)
                            ->orderBy('updated_at', 'desc');
                    });
                return $result;
            }
        }
        $result = $relation
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
}
