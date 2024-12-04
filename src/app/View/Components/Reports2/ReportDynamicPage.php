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
        private $currentParams = []
    ) {}



    private function paginateDataSource($dataSource, $hasPagination, $perPage)
    {
        $page = $_GET['page'] ?? 1;
        if ($hasPagination) {
            $dataSource = (new LengthAwarePaginator($dataSource->forPage($page, $perPage), $dataSource->count(), $perPage, $page));
        }

        return $dataSource;
    }


    private function createIteratorPages($page){
        $iteratorBlock = $page->getIteratorBlock;
        $sqlString = $iteratorBlock->sql_string;
        $iteratorBlockData = $this->getDataSQLString($sqlString);
        return $iteratorBlockData;
    }


    private function getDataPerPage($page, $currentParams) {
        $blockDetails = $page->getBlockDetails->sortBy('order_no');
        $iteratorPages = $this->createIteratorPages($page);
        
        $dataPerPage = [];
        foreach($iteratorPages as $key => $line) {
            $updatedParams = array_merge($currentParams, (array)$line);
            $blockDetailData = $this->getBlockDataSource($blockDetails, $updatedParams);
            foreach(array_values($blockDetailData) as $values ) {
                $block = $values['block'];
                $mark = ($x = $block->renderer_type) ? $x : $block->id;
                $dataPerPage["page_". $key+1][$mark] =  $blockDetailData[$block->id];
            }
        }
        return $dataPerPage;
    }

    public function render()
    {
       $page = $this->page;
       $currentParams = $this->currentParams;

       $dataPerPage = $this->getDataPerPage($page, $currentParams);

       $currentFormattedParams = $this->formatFromAndToDate($currentParams);
        return view('components.reports2.report-dynamic-page', [
            'dataPerPage' => $dataPerPage,
            'report' => $this->report,
            'currentParams' => $this->currentParams,
            'currentFormattedParams' => $currentFormattedParams,
        ]);
    }
}
