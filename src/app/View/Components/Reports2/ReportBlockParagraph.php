<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports\TraitCreateSQLReport2;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateReport;
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

    private function formatDateWithTimezone($date, $timeZoneNumber, $dateDisplayFormat) {
        $date = DateReport::convertToTimezone($date, $timeZoneNumber);
        $date = new DateTime($date);
        return $date->format($dateDisplayFormat);
    }
    private function formatFromAndToDate($currentParams) {
        $timeZoneNumber = User::find(CurrentUser::id())->time_zone;
        $dateDisplayFormat = $currentParams['date_display_format'] ?? '';
        if ($dateDisplayFormat) {
            // Format both from and to dates using a helper method
            $currentParams['from_date'] = $this->formatDateWithTimezone(
                $currentParams['from_date'],
                $timeZoneNumber,
                $dateDisplayFormat
            );
            $currentParams['to_date'] = $this->formatDateWithTimezone(
                $currentParams['to_date'],
                $timeZoneNumber,
                $dateDisplayFormat
            );
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
