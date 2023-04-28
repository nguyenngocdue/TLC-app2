<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Workflow\LibApps;
use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitViewAllFunctions
{
    use TraitEntityAdvancedFilter;
    use TraitSupportPermissionGate;

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $perPage = $settings[$type][Constant::VIEW_ALL]['per_page'] ?? 20;
        $columnLimit = $settings[$type][Constant::VIEW_ALL]['columns'] ?? null;
        $advancedFilter = $settings[$type][Constant::VIEW_ALL]['advanced_filters'] ?? null;
        $basicFilter = $settings[$type][Constant::VIEW_ALL]['basic_filters'] ?? null;
        $chooseBasicFilter = $settings[$type][Constant::VIEW_ALL]['choose_basic_filters'] ?? null;
        $currentFilter = $settings[$type][Constant::VIEW_ALL]['current_filter'] ?? null;
        $refreshPage = $settings[$type][Constant::VIEW_ALL]['refresh_page'] ?? null;
        return [$perPage, $columnLimit, $advancedFilter, $currentFilter, $refreshPage, $basicFilter, $chooseBasicFilter];
    }

    private function getDataSource($advanceFilters = null)
    {
        $propsFilters = $this->advanceFilter();
        $advanceFilters = $this->distributeFilter($advanceFilters, $propsFilters);
        $model = $this->typeModel;
        $search = request('search');
        $instance = App::make($model);
        $eloquentParams = $instance->eloquentParams;
        $eagerLoadParams = array_keys(array_filter($eloquentParams, fn ($item) => in_array($item[0], ['belongsTo', 'hasMany'])));
        $relation = $instance->search($search);

        if (!CurrentUser::isAdmin()) {
            $isUseTree = $this->isUseTree($this->type);
            if ($isUseTree) {
                $ids = $this->getListOwnerIds(auth()->user());
                $result = $relation
                    ->query(function ($q) use ($ids, $advanceFilters, $propsFilters, $eagerLoadParams) {
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
            ->query(function ($q) use ($advanceFilters, $propsFilters, $eagerLoadParams) {
                $this->queryAdvancedFilter($q, $advanceFilters, $propsFilters);

                return $q
                    ->with($eagerLoadParams)
                    ->orderBy('updated_at', 'desc');
            });
        return $result;
    }
}
