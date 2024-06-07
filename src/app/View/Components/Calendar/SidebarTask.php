<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitGetSuffixListenerControl;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use Illuminate\View\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class SidebarTask extends Component
{
    use TraitListenerControl;
    use TraitGetSuffixListenerControl;
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
        //This will be override by getDataSource() of ModalFilterTask.php
        return [];
    }

    public function getSuffix()
    {
        return "_11111";
    }

    private function getListenersOfDropdown2()
    {
        $suffix = $this->getSuffix();
        return [
            [
                'listen_action' => 'reduce',
                'column_name' => 'task_id' . $suffix,
                'listen_to_attrs' => ['getLodsOfTask', 'getDisciplinesOfTask'],
                'listen_to_fields' => ['task_id' . $suffix, 'task_id' . $suffix],
                'listen_to_tables' => ['pj_tasks', 'pj_tasks'],
                'table_name' => 'pj_tasks',
                'triggers' => ['lod_id' . $suffix, 'discipline_id' . $suffix],
                'dev' => true,
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
        $this->renderJSForK();
        $params = $this->getParamsForHasDataSource();
        // dump($params);
        $params['id'] = 'task_id' . $this->getSuffix();
        $params['name'] = 'task_id' . $this->getSuffix();
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
