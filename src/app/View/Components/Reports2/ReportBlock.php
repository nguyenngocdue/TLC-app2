<?php

namespace App\View\Components\Reports2;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;

class ReportBlock extends Component
{
    use TraitReportQueriedData;
    use TraitReportFilter;
    use TraitReportBlockDataSource;
    // use TraitReportCreateTableColumn;

    public function __construct(
        private $report,
        private $blockDetails = [],
        private $currentParams = [],
        private $hasIteratorBlock = false,
    ) {
        // $this->entity_type = $this->report->entity_type;
    }

    private function paginateDataSource($dataSource, $hasPagination, $perPage)
    {
        $page = $_GET['page'] ?? 1;
        if ($hasPagination) {
            $dataSource = (new LengthAwarePaginator($dataSource->forPage($page, $perPage), $dataSource->count(), $perPage, $page));
        }

        return $dataSource;
    }


    public function render()
    {
        $blockDetails = $this->blockDetails;
        $currentParams = $this->currentParams;
        // $perPage = $currentParams['per_page'] ?? 10;

        $blockDataSource = $this->getBlockDataSource($blockDetails, $currentParams);

        // Update currentParams to use UTC time
        $currentFormattedParams = $this->formatFromAndToDate($currentParams);

        return view('components.reports2.report-block', [
            'blockDataSource' => $blockDataSource,
            'reportId' => $this->report->id,
            'currentParams' => $currentParams,
            'currentFormattedParams' => $currentFormattedParams,
            'hasIteratorBlock' => $this->hasIteratorBlock,
        ]);
    }
}
