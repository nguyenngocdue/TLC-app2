<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\Pj_task;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class SidebarTask extends Component
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
        private $multiple = true,
        private $readOnly = false,
        // private $control = 'dropdown2',
        private $control = 'draggable-event2',
        // private $control = 'radio-or-checkbox2',
        private $allowClear = false,
        private $typeToLoadListener = null, //<<Add this to load listenersOfDropdown2
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    private function getDataSource()
    {
        // $field_id = FieldSeeder::getIdFromFieldName('getLodsOfTask');
        $dataSource = Pj_task::select('id', 'name', 'description')
            ->orderBy('name')
            ->get();
        foreach ($dataSource as &$line) {
            $line->{"getDisciplinesOfTask()"} = $line->getDisciplinesOfTask()->pluck('id');
            $line->{"getLodsOfTask()"} = $line->getLodsOfTask()->pluck('id');
        }
        return $dataSource;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->renderJSForK();
        $params = $this->getParamsForHasDataSource();
        // dump($params);
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
