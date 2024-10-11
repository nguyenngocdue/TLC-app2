<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports\TraitCreateSQLReport2;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class ReportBlockParagraph extends Component
{
    use TraitReportQueriedData;
    use TraitCreateSQLReport2;
    use TraitReportFilter;
    use TraitReportDetectVariableChanges;

    public function __construct(
        private $queriedData,
        private $block,
        private $reportId,
        private $currentParams,
        private $currentFormattedParams,
    ) {}

    public function render()
    {
        $block = $this->block;
        $currentFormattedParams = $this->currentFormattedParams;
        $queriedData = $this->queriedData;
        $strHtml = $block->html_content;
        $htmlRender = $this->detectVariables($strHtml, $currentFormattedParams, $queriedData);

        return view('components.reports2.report-block-paragraph', [
            'htmlRender' => Blade::render($htmlRender),
            'block' => $block
        ]);
    }
}
