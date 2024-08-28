<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewEditFunctions;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class CalendarGrid extends Component

{
    function render()
    {
        dd("CalendarGrid Renderer");
    }
    // use TraitViewEditFunctions;
    // /**
    //  * Create a new component instance.
    //  *
    //  * @return void
    //  */
    // public function __construct(
    //     private $readOnly = true,
    //     private $timesheetableType = null,
    //     private $timesheetableId = null,
    //     private $apiUrl = null,
    //     private $arrHidden = null,
    //     private $type = null,
    // ) {
    //     //
    //     dd("HELLO");
    // }

    // /**
    //  * Get the view / contents that represent the component.
    //  *
    //  * @return \Illuminate\Contracts\View\View|\Closure|string
    //  */
    // public function render()
    // {
    //     $statusTimeSheet = $this->timesheetableType::findFromCache($this->timesheetableId)->status ?? null;
    //     $hasRenderSidebar = true;
    //     if ($statusTimeSheet && in_array($statusTimeSheet, ['pending_approval', 'approved'])) {
    //         $hasRenderSidebar = false;
    //     }

    //     $sheetOwner = $this->getSheetOwner($this->timesheetableType, $this->timesheetableId);

    //     return view('components.calendar.calendar-grid', [
    //         'readOnly' => $this->readOnly,
    //         'timesheetableType' => $this->timesheetableType,
    //         'timesheetableId' => $this->timesheetableId,
    //         'apiUrl' => $this->apiUrl,
    //         'arrHidden' => $this->arrHidden,
    //         'type' => $this->type,
    //         'hasRenderSidebar' => $hasRenderSidebar,
    //         'sheetOwner' => $sheetOwner,
    //     ]);
    // }
}
