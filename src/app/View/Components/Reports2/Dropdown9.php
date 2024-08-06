<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;

class Dropdown9 extends Component
{
    //for report 2
    public function __construct(
        private $dataSource = [],
        private $name = 'No name',
        private $currentParams = [],
        private $title = "No title",
        private $allowClear = false,
        private $routeName = '',

        private $filterDetails = '',

        private $entityType = '',
        private $entityType2 = '',
        private $reportId = '',

    ) {
    }


    public function render()
    {
        $currentParams = $this->currentParams;
        $filterDetails = (object)$this->filterDetails;

        $paramsCurrentRp = [];
        $filterDetails->map(function ($value) use ($currentParams, &$paramsCurrentRp) {
            $nameId = str_replace('_name', '_id', $value->getColumn->data_index);
            if (isset($currentParams[$nameId])) {
                $paramsCurrentRp[$nameId] = $currentParams[$nameId];
            }
        });


        return view('components.reports2.dropdown9', [
            'dataSource' =>  $this->dataSource,
            'name' => $this->name,
            'currentParams' => $currentParams,
            'paramsCurrentRp' => $paramsCurrentRp,
            'name' => $this->name,
            'title' => $this->title,
            'allowClear' => $this->allowClear,
            'routeName' => $this->routeName,
            'entityType' => $this->entityType,
            'entityType2' => $this->entityType2,
            'reportId' => $this->reportId

        ]);
    }
}
