<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Http\Resources\HrTsLineCollection;
use App\Models\Hr_timesheet_officer;
use App\Models\Public_holiday;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimeSheetOfficerController extends TimesheetController
{
    use TraitViewAllFunctions;
    protected $type = 'hr_timesheet_officers';
    protected $model = Hr_timesheet_officer::class;
    public function show(Request $request, $id)
    {
        [, $filterViewAllCalendar] = $this->getUserSettingsViewAllCalendar();
        $year = $filterViewAllCalendar['year'] ?? Carbon::now()->format('Y');
        $hrTimesheetOfficer = Hr_timesheet_officer::findOrFail($id);
        $ownerId = $hrTimesheetOfficer->owner_id ?? CurrentUser::id();
        $workplaceId = User::findFromCache($ownerId)->workplace;
        $hrTsLines = $hrTimesheetOfficer->getHrTsLines;
        $initialDate = $hrTimesheetOfficer->week;
        $publicHoliday = Public_holiday::where('year',$year)->where('workplace_id',$workplaceId)->get();
        $collectionMerge = $hrTsLines->merge($publicHoliday);
        return ['hits' => new HrTsLineCollection($collectionMerge), 'meta' => $initialDate,];
    }
}
