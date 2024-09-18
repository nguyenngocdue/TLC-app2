<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports\TraitCreateSQLReport2;
use DateTime;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class ReportBlockParagraph extends Component
{
    use TraitReportQueriedData;
    use TraitCreateSQLReport2;
    use TraitReportFilter;

    public function __construct(
        private $block,
        private $reportId,
        private $currentParams,
    ) {}

    private function renderHtml($strHtml, $currentParams)
    {
        $html = $this->replaceVariableStrs($strHtml, $currentParams);
        return $html;
    }

    private function formatFromAndToDate($currentParams) {
        $dateDisplayFormat = $currentParams['date_display_format'] ?? '';
        if ($dateDisplayFormat) {
            $fromDate = new DateTime($currentParams['from_date']);
            $toDate = new DateTime($currentParams['to_date']);
            $fromDate = $fromDate->format($dateDisplayFormat);
            $toDate = $toDate->format($dateDisplayFormat);
            $currentParams['to_date'] = $toDate;
            $currentParams['from_date'] = $fromDate;
        }
        return $currentParams;
    }
    public function render()
    {
        $block = $this->block;
        $currentParams = $this->currentParams;
        $currentParams = $this->formatFromAndToDate($currentParams);

        $strHtml = $block->html_content;
        $htmlRender = $this->renderHtml($strHtml, $currentParams);

        return view('components.reports2.report-block-paragraph', [
            'htmlRender' => Blade::render($htmlRender),
            'block' => $block
        ]);
    }
}
