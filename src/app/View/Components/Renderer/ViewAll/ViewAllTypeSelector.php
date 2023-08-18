<?php

namespace App\View\Components\Renderer\ViewAll;

use App\Utils\Support\JsonControls;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class ViewAllTypeSelector extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type,
        private $viewType,
    ) {
        //
    }

    private function getTabs()
    {
        $tabs = [];
        $tableName = Str::plural($this->type);
        $home = [
            'href' => "?view_type=table&action=updateViewAllMode&_entity=$tableName",
            'title' => "List View",
            'icon' => 'fa-duotone fa-list',
            'active' => $this->viewType == 'list-view',
        ];
        if (in_array($tableName, JsonControls::getAppsHaveViewAllCalendar())) {
            $tabs = [
                'home' => $home,
                'calendar' => [
                    'href' => "?view_type=calendar&action=updateViewAllMode&_entity=$tableName",
                    'title' => "Calendar All",
                    'icon' => 'fa-duotone fa-calendar',
                    'active' => $this->viewType == 'calendar-view',
                ]
            ];
        };
        if (in_array($tableName, JsonControls::getAppsHaveViewAllMatrix())) {
            $tabs = [
                'home' => $home,
                'calendar' => [
                    'href' => "?view_type=matrix&action=updateViewAllMode&_entity=$tableName",
                    'title' => "Matrix View",
                    'icon' => 'fa-duotone fa-table',
                    'active' => $this->viewType == 'matrix-view',
                ]
            ];
        };
        if (in_array($tableName, JsonControls::getAppsHaveViewAllMatrixPrint())) {
            $tabs['print'] = [
                    'href' => "?view_type=matrix_print&action=updateViewAllMode&_entity=$tableName",
                    'title' => "Print Matrix View",
                    'icon' => 'fa-duotone fa-table',
                    'active' =>  $this->viewType == 'matrix-print-view',
            ];
        };
        return $tabs;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view(
            'components.renderer.view-all.view-all-type-selector',
            [
                'tabs' => $this->getTabs(),
            ]
        );
    }
}
