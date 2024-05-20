<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Http\Resources\HrTsLineCollection;
use App\Models\Diginet_employee_leave_line;
use App\Models\Hr_timesheet_officer;
use App\Models\Public_holiday;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TimeSheetOfficerController extends TimesheetController
{
    use TraitViewAllFunctions;
    protected $type = 'hr_timesheet_officers';
    protected $model = Hr_timesheet_officer::class;

    private function getPublicHoliday($hrTimesheetOfficer)
    {
        [, $filterViewAllCalendar] = $this->getUserSettingsViewAllCalendar();
        $year = $filterViewAllCalendar['year'] ?? Carbon::now()->format('Y');

        $ownerId = $hrTimesheetOfficer->owner_id ?? CurrentUser::id();
        $workplaceId = User::findFromCache($ownerId)->workplace;
        $publicHoliday = Public_holiday::where('year', $year)->where('workplace_id', $workplaceId)->get();
        return $publicHoliday;
    }

    private function getLeaveApplication($hrTimesheetOfficer)
    {
        // Log::info($hrTimesheetOfficer);
        $ownerId = $hrTimesheetOfficer->owner_id;
        $user = User::findFromCache($ownerId);
        // Log::info($user);
        $employeeId = $user->employeeid;
        $x = (new Diginet_employee_leave_line())->getLinesByEmployeeIdAndRange($employeeId);
        Log::info($x);
        return $x;
    }

    public function show(Request $request, $id)
    {
        $hrTimesheetOfficer = Hr_timesheet_officer::findOrFail($id);
        $hrTsLines = $hrTimesheetOfficer->getHrTsLines;

        $ph = $this->getPublicHoliday($hrTimesheetOfficer);
        $collectionMerge = $hrTsLines->merge($ph);

        $la = $this->getLeaveApplication($hrTimesheetOfficer);
        $collectionMerge = $collectionMerge->merge($la);

        $initialDate = $hrTimesheetOfficer->week;
        return ['hits' => new HrTsLineCollection($collectionMerge), 'meta' => $initialDate,];
    }
}
