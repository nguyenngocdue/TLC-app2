<?php

namespace App\View\Components\Calendar;

use App\Models\Hr_timesheet_officer;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class NavigationCalendar extends Component
{
    function __construct(
        private $timesheetId,
        private $owner,
    ) {
        //
    }

    private function slice($tss)
    {

        $currentIndex = 0;
        foreach ($tss as $index => $ts) {
            if ($ts->id == $this->timesheetId) {
                $currentIndex = $index;
                break;
            }
        }
        $start = max(0, $currentIndex - 3);
        $result = $tss->slice($start, 7)->values();
        return $result;
    }

    function updateUserSettings()
    {
        $cu = CurrentUser::get();
        $userSettings = $cu->settings;
        $userSettings["hr_timesheet_officers"][Constant::VIEW_ALL]['calendar']['owner_id'] = [$this->owner->id];

        $cu->settings = $userSettings;
        $cu->save();
    }

    function render()
    {
        $this->updateUserSettings();
        $tss = Hr_timesheet_officer::query()
            ->where('owner_id', $this->owner->id)
            ->orderBy('week')
            ->get();
        $tss = $this->slice($tss);

        return view('components.calendar.navigation-calendar', [
            'tss' => $tss,
            'timesheetId' => $this->timesheetId,
        ]);
    }
}
