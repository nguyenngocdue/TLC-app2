<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports\TraitCreateSQLReport2;
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
    public function render()
    {
        $block = $this->block;
        $currentParams = $this->currentParams;

        $strHtml = $block->html_content;
        $htmlRender = $this->renderHtml($strHtml, $currentParams);

        return view('components.reports2.report-block-paragraph', [
            'htmlRender' => Blade::render($htmlRender),
            'block' => $block
        ]);
    }
}
