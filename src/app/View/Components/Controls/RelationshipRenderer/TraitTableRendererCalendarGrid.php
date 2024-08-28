<?php

namespace App\View\Components\Controls\RelationshipRenderer;

use App\Models\User;
use Carbon\Carbon;
use App\Utils\Support\DateTimeConcern;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

trait TraitTableRendererCalendarGrid
{
    private function renderCalendarGrid($id, $modelPath, $row, $type, $readOnly)
    {
        $arrHidden = [];
        $dateTime = Carbon::parse($row->week);
        $weekNumber = $dateTime->weekOfYear;

        // dump($modelPath, $id);
        $timeSheet = $modelPath::findFromCache($id);
        // dump($timeSheet);
        $sheetOwner = User::findFromCache($timeSheet->owner_id);
        // dump($sheetOwner);
        $useTsForPayroll = $sheetOwner->use_ts_for_payroll ?? false;
        // dump($useTsForPayroll);

        if ($useTsForPayroll) {
            if ($dateTime->day == 26) {
                $dayOfWeek = $dateTime->dayOfWeek;
                $arrHidden = DateTimeConcern::getDayHiddenForDayIndexWeek($dayOfWeek);
            } else {
                $dateTimeDay25 = Carbon::createFromDate($dateTime->year, $dateTime->month, 25);
                $weekOfDay25 = $dateTimeDay25->weekOfYear;
                if ($weekOfDay25 == $weekNumber) {
                    $dayOfWeekStart = $dateTime->dayOfWeek;
                    $dayOfWeekEnd = $dateTimeDay25->dayOfWeek;
                    $arrHidden = DateTimeConcern::getDayHiddenForDayIndexWeek($dayOfWeekStart, $dayOfWeekEnd);
                }
            }
        }

        // $index = strpos($type, "_");
        // $typeEdit = substr($type, $index + 1);
        $typePlural = Str::plural($type);
        $typeEdit = "calendar_" . $typePlural;
        // Log::info($typeEdit);
        // dump($typeEdit);
        $apiUrl = route($typeEdit . '.index');
        // $token = CurrentUser::getTokenForApi();
        $statusTimeSheet = $modelPath::findFromCache($id)->status ?? null;
        $hasRenderSidebar = true;
        if ($statusTimeSheet && in_array($statusTimeSheet, ['pending_approval', 'approved'])) {
            $hasRenderSidebar = false;
        }
        if ($readOnly) {
            $hasRenderSidebar = false;
        }
        $params = [
            'timesheetableType' => $modelPath,
            'timesheetableId' => $id,
            'apiUrl' => $apiUrl,
            'readOnly' => $this->readOnly || $readOnly,
            'arrHidden' => $arrHidden,
            'type' => $type,
            'hasRenderSidebar' => $hasRenderSidebar,
            'sheetOwner' => $sheetOwner,
            "hidden" => $readOnly
        ];

        return view('components.calendar.calendar-grid', $params);
    }
}
