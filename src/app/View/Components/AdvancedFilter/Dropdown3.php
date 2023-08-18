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
        
        return view('components.advanced-filter.dropdown3', [
            'dataSource' => $this->getDataSource($params,$typeRelationship,$filterColumns,$filterValues),
            'name' =>  $this->name,
            'valueSelected' => $this->valueSelected,
        ]);
    }
    private function getDataSource($params,$typeRelationship,$filterColumns,$filterValues){
        if (!empty($filterColumns) && !empty($filterValues)) {
            $arrayQuery = [];
            foreach ($filterColumns as $key => $value) {
                if (isset($arrayQuery[$value])) {
                    $arrayQuery[$value] = [
                        $arrayQuery[$value],
                        $filterValues[$key]
                    ];
                } else {
                    $arrayQuery[$value] = $filterValues[$key];
                }
            }
            if ($typeRelationship == 'eloquent') {
                return ($params[1])::where(function ($q) use ($arrayQuery, $typeRelationship) {
                    foreach ($arrayQuery as $key => $value) {
                        is_array($value) ? $q->whereIn($key, $value) : $q->where($key, $value);
                    }
                    return $q;
                })->get();
            } 
            $allModel =  ($params[1])::all();
            $keyFirst = array_key_first($arrayQuery);
            $valueFirst = $arrayQuery[$keyFirst];
            $results = $allModel->filter(function($item) use ($keyFirst,$valueFirst){
                return $item->{$keyFirst}->where('id', $valueFirst)->count() > 0;
            });
            return $results;
        } else {
            if (isset($params[1])) {
                if ($params[1] == 'App\Models\User') {
                    return static::singletonCache();
                }
                return ($params[1])::all();
            }
            return collect();
        }
    }
}