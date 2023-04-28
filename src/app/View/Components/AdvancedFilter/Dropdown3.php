<?php

namespace App\View\Components\AdvancedFilter;

use App\Models\User;
use Illuminate\View\Component;

class Dropdown3 extends Component
{
    static $singleton = null;
    static function singletonCache()
    {
        if (is_null(static::$singleton)) {
            static::$singleton = User::all();
        }
        return static::$singleton;
    }

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $relationships = [],
        private $name = '',
        private $valueSelected = null,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dataRelationShips = $this->relationships;
        if (empty($dataRelationShips)) return "Relationship not found";
        $params = $dataRelationShips['eloquentParams'] ?? ($dataRelationShips['oracyParams'] ?? "");
        $filterColumns = $dataRelationShips['filter_columns'];
        $filterValues = $dataRelationShips['filter_values'];
        if (!empty($filterColumns) && !empty($filterValues)) {
            $arrayQuery = [];
            foreach ($filterColumns as $key => $value) {
                if (isset($arrayQuery[$value])) {
                    $result = [];
                    $result[] = $arrayQuery[$value];
                    $result[] = $filterValues[$key];
                    $arrayQuery[$value] = $result;
                } else {
                    $arrayQuery[$value] = $filterValues[$key];
                }
            }
            $dataSource = ($params[1])::where(function ($q) use ($arrayQuery) {
                foreach ($arrayQuery as $key => $value) {
                    is_array($value) ? $q->whereIn($key, $value) : $q->where($key, $value);
                }
                return $q;
            })->get();
        } else {
            if ($params[1] == 'App\Models\User') {
                $dataSource = static::singletonCache();
            } else {
                $dataSource = ($params[1])::all();
            }
        }
        return view('components.advanced-filter.dropdown3', [
            'dataSource' => $dataSource,
            'name' =>  $this->name,
            'valueSelected' => $this->valueSelected,
        ]);
    }
}
