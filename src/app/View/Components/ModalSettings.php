<?php

namespace App\View\Components;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityExportCSV;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\Props;
use App\Utils\Support\Json\SuperProps;
use Illuminate\View\Component;

class ModalSettings extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $type, private $title = "Settings") {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $allColumns = $this->getColumns($this->type);
        $settings = CurrentUser::getSettings();
        //If the setting array has not been set, it means this is the 1st time user accessing this module
        //Therefore all columns must be selected
        $selected = $settings[$this->type][Constant::VIEW_ALL]['columns'] ?? $allColumns;
        return view('components.modal-settings', [
            'type' => $this->type,
            'title' => $this->title,
            'allColumns' => $allColumns,
            'selected' => $selected,
        ]);
    }
    private function getColumns($type)
    {
        $props = SuperProps::getFor($type)['props'];
        return $props = array_filter($props, function ($prop) {
            return !$prop['hidden_view_all'] && in_array($prop['column_type'], ['static_heading', 'static_control']);
        });
    }
}
