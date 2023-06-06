<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\Sub_project;
use App\Utils\ClassList;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class SidebarFilterSubProject extends Component
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
        private $typeToLoadListener = null,
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    private function getDataSource()
    {
        $dataSource = Sub_project::select('id', 'name', 'description', 'project_id', 'lod_id')->get();
        return $dataSource;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // dump("Selected: '" . $this->selected . "'");
        $tableName =  $this->tableName;
        $params = [
            'name' => $this->name,
            'id' => $this->name,
            'selected' => $this->selected,
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $tableName,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::DROPDOWN,
            // 'entity' => $this->type,
            'multiple' => $this->multiple ? true : false,
            'allowClear' => $this->allowClear,
        ];
        $this->renderJSForK($tableName);
        // dump($params);
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
