<?php

namespace App\View\Components\Renderer;

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
    ) {
        //
    }

    private function getTabs()
    {
        $tabs = [];
        $tableName = Str::plural($this->type);
        $home = [
            'href' => "?view_type=table&action=updateViewAllMode&_entity=$tableName",
            'title' => "View All Table",
            'icon' => 'fa-solid fa-house',
            'active' => true,
        ];
        if (in_array($tableName, JsonControls::getAppsHaveViewAllCalendar())) {
            $tabs = [
                'home' => $home,
                'calendar' => [
                    'href' => "?view_type=calendar&action=updateViewAllMode&_entity=$tableName",
                    'title' => "View All Calendar",
                    'icon' => 'fa-regular fa-calendar',
                    'active' => false,
                ]
            ];
        };
        if (in_array($tableName, JsonControls::getAppsHaveViewAllMatrix())) {
            $tabs = [
                'home' => $home,
                'calendar' => [
                    'href' => "?view_type=matrix&action=updateViewAllMode&_entity=$tableName",
                    'title' => "View All Matrix",
                    'icon' => 'fa-regular fa-table',
                    'active' => false,
                ]
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
            'components.renderer.view-all-type-selector',
            ['tabs' => $this->getTabs()]
        );
    }
}
