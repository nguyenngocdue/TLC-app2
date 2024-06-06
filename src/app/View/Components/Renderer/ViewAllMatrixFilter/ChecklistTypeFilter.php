<?php

namespace App\View\Components\Renderer\ViewAllMatrixFilter;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\Qaqc_insp_tmpl;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class ChecklistTypeFilter extends Component
{
    use TraitListenerControl;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $id,
        private $name,
        private $tableName,
        private $selected = "",
        private $multiple = false,
        private $control = 'dropdown2', // or 'radio-or-checkbox2'
        private $readOnly = false,
        private $allowClear = false,
        private $typeToLoadListener = null, //<<Add this to load listenersOfDropdown2
    ) {
        // if (old($name)) $this->selected = old($name);
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    private function getDataSource()
    {
        $db = Qaqc_insp_tmpl::select('id', 'name', 'description')
            ->orderBy('name')
            ->with('getProdRoutingsOfInspTmpl')
            ->get();
        // Oracy::attach("getProdRoutingsOfInspTmpl()", $db);
        return $db;

        // $matrixView = (new QaqcInspChklstShts());
        // $dataSource = $matrixView->getMultipleMatrixObjects();
        // $objects = [];
        // foreach ($dataSource as $value) {
        //     $objects[] = $value['chklst_tmpls'];
        // }

        // $uniqueObjectsById = [];

        // // Loop over each object
        // foreach ($objects as $obj) {
        //     if (!isset($uniqueObjectsById[$obj->id])) {
        //         // Add the object to the array using id as key
        //         $uniqueObjectsById[$obj->id] = $obj;
        //     }
        // }

        // // Get the unique objects in a regular indexed array
        // $uniqueObjects = array_values($uniqueObjectsById);

        // return $uniqueObjects;
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
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
