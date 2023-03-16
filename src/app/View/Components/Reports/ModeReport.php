<?php

namespace App\View\Components\Reports;

use Illuminate\View\Component;

class ModeReport extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $itemsSelected = [],
        private $dataSource = [],
        private $entity = '',
        private $typeReport = '',
        private $formName = '',
        private $userId = '',
        private $column = '',
    ) {
        //
    }

    public function render()
    {

        $name = key($this->dataSource);
        $title = ucwords(str_replace('_', " ", $name));
        $dataRender = $this->dataSource[key($this->dataSource)];
        return view('components.reports.mode-report', [
            'formName' => $this->formName,
            'itemsSelected' => $this->itemsSelected,
            'dataRender' => $dataRender,
            'entity' => $this->entity,
            'typeReport' => $this->typeReport,
            'title' => $title,
            'name' => $name,
            'userId' => $this->userId,
            'column' => $this->column
        ]);
    }
}
