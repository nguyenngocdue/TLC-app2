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
            static::$singleton = User::where('show_on_beta', 0)->get();
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
        $typeRelationship = isset($dataRelationShips['eloquentParams']) ? 'eloquent' : 'oracy';
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
            if ($typeRelationship == 'eloquent') {
                $dataSource = ($params[1])::where(function ($q) use ($arrayQuery, $typeRelationship) {
                    foreach ($arrayQuery as $key => $value) {
                        is_array($value) ? $q->whereIn($key, $value) : $q->where($key, $value);
                    }
                    return $q;
                })->get();
            } else {
                dump('Oracy relationship is not yet implemented.');
                $dataSource = [];
                // $dataSource = ($params[1])::{$params[0]}($arrayQuery[0], $arrayQuery[1])->get();
            }
        } else {
            if (isset($params[1])) {
                if ($params[1] == 'App\Models\User') {
                    $dataSource = static::singletonCache();
                } else {
                    //<<This will hide production order
                    // if ($params[1]::get()->count() > 1000) {
                    //     dump('Number of items over 1000 please render the component -> number');
                    //     return '';
                    // }
                    $dataSource = ($params[1])::all();
                }
            } else {
                $dataSource = collect();
            }
        }
        return view('components.advanced-filter.dropdown3', [
            'dataSource' => $dataSource,
            'name' =>  $this->name,
            'valueSelected' => $this->valueSelected,
        ]);
    }
}