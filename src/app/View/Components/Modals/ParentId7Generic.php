<?php

namespace App\View\Components\Modals;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use Illuminate\View\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ParentId7Generic extends Component
{
    use TraitListenerControl;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $tableName,
        private $selected = "",
        private $multiple = false,
        private $readOnly = false,
        private $control = 'dropdown2', // or 'radio-or-checkbox2'
        private $allowClear = false,
        private $typeToLoadListener = 'any_thing_but_not_null',

        private $span = null,
        private $groupIdName = null,
        private $dataSourceTableName = null,
        private $eloquentFunctionName = null,
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    private function getDataSource()
    {

        $modalPath = Str::modelPathFrom($this->dataSourceTableName);
        $all = $modalPath::query();

        if (method_exists($modalPath, $this->eloquentFunctionName)) {
            $all = $all->with($this->eloquentFunctionName);
        }
        switch ($this->dataSourceTableName) {
            case 'users':
                $all = $all->whereNot('resigned', 1);
                $all = $all->with('getPosition');
                $all = $all->with('getAvatar');
                break;
        }
        $all = $all->get();
        $all = $all->map(function ($item) {
            $groupIdName = null;
            if ($this->eloquentFunctionName) {
                $className = get_class($item->{$this->eloquentFunctionName}());

                switch ($className) {
                    case 'Illuminate\Database\Eloquent\Relations\BelongsToMany':
                        $groupIdName = $item->{$this->eloquentFunctionName}->pluck('id')->toArray();
                        break;
                    case 'Illuminate\Database\Eloquent\Relations\BelongsTo':

                        break;
                }
            }

            $newItem = [
                'id' => $item->id,
                'name' => $item->name,
                'subtitle' => $item->description ?? $item->getPosition->name ?? null,
                $this->groupIdName => $groupIdName,
            ];
            if (method_exists($item, 'getAvatar')) {
                if ($item->getAvatar) {
                    $newItem['avatar'] = app()->pathMinio() . $item->getAvatar->url_thumbnail;
                } else {
                    $newItem['avatar'] = '/images/avatar.jpg';
                }
            }
            return (object) $newItem;
        });
        return $all;
        // return [
        //     (object) ['name' => 'name1', 'id' => 1, $this->groupIdName => 1],
        //     (object) ['name' => 'name2', 'id' => 2, $this->groupIdName => 1],
        //     (object) ['name' => 'name4', 'id' => 4, $this->groupIdName => 2],
        //     (object) ['name' => 'name5', 'id' => 5, $this->groupIdName => 2],
        // ];
    }

    private function getListenersOfDropdown2()
    {
        return [
            [
                'listen_action' => 'reduce',
                'column_name' => $this->name,
                'listen_to_attrs' => [$this->groupIdName],
                'listen_to_fields' => [$this->name],
                'listen_to_tables' => [$this->tableName],
                'table_name' => $this->tableName,
                // 'attrs_to_compare' => ['id'],
                'triggers' => [$this->groupIdName],
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $params = $this->getParamsForHasDataSource();
        $params['span'] = $this->span ?: 3;
        $this->renderJSForK();
        // dump($this->control);
        // dump($params);   
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
