<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

trait TraitViewAllFunctions
{
    use TraitEntityAdvancedFilter;

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $perPage = $settings[$type][Constant::VIEW_ALL]['per_page'] ?? 20;
        $columnLimit = $settings[$type][Constant::VIEW_ALL]['columns'] ?? null;
        $advancedFilter = $settings[$type][Constant::VIEW_ALL]['advanced_filters'] ?? null;
        return [$perPage, $columnLimit, $advancedFilter];
    }

    private function getDataSource($advanceFilters = null)
    {
        $propsFilters = $this->advanceFilter();
        $advanceFilters = $this->distributeFilter($advanceFilters, $propsFilters);
        $model = $this->typeModel;
        $search = request('search');
        $result = App::make($model)::search($search)
            ->query(function ($q) use ($advanceFilters, $propsFilters) {
                $this->queryAdvancedFilter($q, $advanceFilters, $propsFilters);
                return $q->orderBy('updated_at', 'desc');
            });
        return $result;
    }
}
