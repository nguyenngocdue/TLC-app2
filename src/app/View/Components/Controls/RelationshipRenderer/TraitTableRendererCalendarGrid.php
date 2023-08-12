<?php

namespace App\View\Components\Controls\RelationshipRenderer;

use Carbon\Carbon;
use App\Utils\Support\DateTimeConcern;

trait TraitTableRendererCalendarGrid
{
    private function renderCalendarGrid($id, $modelPath, $row, $type)
    {
        $arrHidden = [];
        $dateTime = Carbon::parse($row->week);
        $weekNumber = $dateTime->weekOfYear;
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
        $index = strpos($type, "_");
        $typeEdit = substr($type, $index + 1);
        $apiUrl = route($typeEdit . '.index');
        // $token = CurrentUser::getTokenForApi();
        $statusTimeSheet = $modelPath::findFromCache($id)->status ?? null;
        $hasRenderSidebar = true;
        if ($statusTimeSheet && in_array($statusTimeSheet, ['pending_approval', 'approved'])) {
            $hasRenderSidebar = false;
        }
        return view('components.calendar.calendar-grid', [
            'timesheetableType' => $modelPath,
            'timesheetableId' => $id,
            'apiUrl' => $apiUrl,
            'readOnly' => $this->readOnly,
            'arrHidden' => $arrHidden,
            'type' => $type,
            'hasRenderSidebar' => $hasRenderSidebar,
        ]);
    }
}
