<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitGetSuffixListenerControl;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewEditFunctions;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class FullCalendar extends Component
{
    use TraitGetSuffixListenerControl;
    use TraitViewEditFunctions;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $timesheetableType,
        private $timesheetableId,
        private $apiUrl,
        private $readOnly = false,
        private $arrHidden = [],
        private $sheetOwner = null,
        private $hiddenCalendarHeader = false,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $token = CurrentUser::getTokenForApi();

        // $sheetOwnerId = ($this->timesheetableType)::findFromCache($this->timesheetableId)->sheetOwner_id ?? CurrentUser::id();
        // $year = ($this->timesheetableType)::findFromCache($this->timesheetableId)->year ?? date('Y');
        $workplace = $this->sheetOwner->workplace;
        $timeBreaks = $this->getTimeBreaksByWorkplace($workplace);
        return view('components.calendar.full-calendar', [
            'modalId' => 'calendar001',
            'timesheetableType' => $this->timesheetableType,
            'timesheetableId' => $this->timesheetableId,
            'apiUrl' => $this->apiUrl,
            'token' => $token,
            'readOnly' => $this->readOnly,
            'arrHidden' => $this->arrHidden,
            'timeBreaks' => $timeBreaks,
            'suffix' => $this->getSuffix(),
            'sheetOwner' => $this->sheetOwner,
            'hiddenCalendarHeader' => $this->hiddenCalendarHeader,
            // 'year' => $year,
        ]);
    }
    private function getTimeBreaksByWorkplace($workplace)
    {
        switch ($workplace) {
            case 1:
            case 6:
                return ['12:00:00', '12:30:00'];
                break;
            case 2:
            case 3:
            case 4:
                return ['11:30:00', '12:00:00'];
                break;
            case 5:
                return ['12:00:00', '12:30:00'];
                break;
            default:
                break;
        }
    }
    private function getSuffix()
    {
        return '_11111';
    }
}
