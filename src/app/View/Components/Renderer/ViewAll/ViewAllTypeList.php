<?php

namespace App\View\Components\Renderer\ViewAll;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class ViewAllTypeList extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $tabPane,
        private $type,
        private $perPage,
        private $refreshPage,
        private $trashed,
        private $columns,
        private $dataSource,
        private $tableTrueWidth,
    ) {
        //
    }

    function removeStatusColumnIfStatusless()
    {
        $isStatusless = (Str::modelPathFrom($this->type))::isStatusless();
        if ($isStatusless) $this->columns = array_filter($this->columns, fn($c) => $c['dataIndex'] !== 'status');
        // dump($this->columns);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->removeStatusColumnIfStatusless();
        $route = route('updateUserSettings');
        $perPage = "<x-form.per-page type='$this->type' route='$route' perPage='$this->perPage'/>";
        $actionButtonGroup = "<div class='flex' component='actionButtonGroup'>
                    <x-form.refresh type='$this->type' route='$route' valueRefresh='$this->refreshPage'/>
                    <x-form.action-button-group type='$this->type' />
                </div>";
        $actionMultipleGroup = $this->trashed ? "<x-form.action-multiple type='$this->type' restore='true'/>" : "<x-form.action-multiple type='$this->type'/>";
        return view(
            'components.renderer.view-all.view-all-type-list',
            [
                'tabPane' => $this->tabPane,
                'columns' => $this->columns,
                'dataSource' => $this->dataSource,
                'tableTrueWidth' => $this->tableTrueWidth,

                'perPage' => $perPage,
                'actionButtonGroup' => $actionButtonGroup,
                'actionMultipleGroup' => $actionMultipleGroup,
            ]
        );
    }
}
