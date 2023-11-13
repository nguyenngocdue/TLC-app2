<?php

namespace App\View\Components\Renderer\ViewAllMatrixFilter;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class YearFilter extends Component
{
    // use TraitListenerControl;
    // use TraitGetSuffixListenerControl;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $id,
        private $name,
        // private $tableName,
        private $selected = "",
        private $multiple = false,
        private $readOnly = false,
        private $control = 'dropdown2', // or 'radio-or-checkbox2'
        private $allowClear = false,
        // private $typeToLoadListener = null, //<<Add this to load listenersOfDropdown2
    ) {
        $this->selected = date('Y', $selected);
        // $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    private function getDataSource()
    {
        $thisYear = date('Y');
        $selectedYear = $this->selected;
        $result = [];
        for ($i = $selectedYear - 2; $i < $selectedYear + 3; $i++) $result[$i] = $i;
        $result[$thisYear] = $thisYear;
        if (CurrentUser::isAdmin()) $result[$thisYear] .= ".";
        return $result;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.controls.has-data-source.dropdown-raw', [
            'id' => $this->id,
            'name' => $this->name,
            'selected' => [$this->selected],
            'dataSource' => $this->getDataSource(),
        ]);
    }
}
