<?php

namespace App\View\Components\Reports2;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class ReportDynamicPage extends Component
{
    use TraitReportFilter;
    use TraitReportQueriedData;
    use TraitReportTermNames;
    use TraitReportBlockDataSource;

    public function __construct(
        private $page = null,
        private $report = null,
        private $currentParams = [],
        private $hasIteratorBlock = false,
    ) {}



    private function createIteratorPages($page, $currentParams)
    {
        $iteratorBlock = $page->getIteratorBlock;
        $sqlString = $iteratorBlock->sql_string;
        $sqlString = $this->getSql($sqlString, $currentParams);
        $iteratorBlockData = $this->getDataSQLString($sqlString);
        return $iteratorBlockData;
    }


    private function getDataPerPage($page, $currentParams)
    {
        $iteratorPages = $this->createIteratorPages($page, $currentParams);
        $dataPerPage = [];
        foreach ($iteratorPages as $key => $line) {
            $updatedParams = array_merge($currentParams, (array)$line);
            $dataPerPage["page_" . $key + 1] = [
                'updatedParams' => $updatedParams,
                'page' => $page,
            ];
        }
        return $dataPerPage;
    }

    public function render()
    {
        $page = $this->page;
        $currentParams = $this->currentParams;
        $dataPerPage = $this->getDataPerPage($page, $currentParams);
        return view('components.reports2.report-dynamic-page', [
            'dataPerPage' => $dataPerPage,
            'report' => $this->report,
            'hasIteratorBlock' => $this->hasIteratorBlock
        ]);
    }
}
