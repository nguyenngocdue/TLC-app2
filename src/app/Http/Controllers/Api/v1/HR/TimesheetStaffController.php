<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\TimesheetStaffRequest;
use App\Http\Resources\HrTsLineCollection;
use App\Http\Resources\HrTsLineStoreResource;
use App\Http\Resources\HrTsLineUpdateResource;
use App\Models\Hr_timesheet_officer;
use App\Services\Hr_timesheet_line\Hr_timesheet_lineServiceInterface;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimesheetStaffController extends Controller
{
    public $timesheetLineService;
    public function __construct(Hr_timesheet_lineServiceInterface $timesheetLineService)
    {
        $this->timesheetLineService = $timesheetLineService;
    }
    public function show(Request $request, $id)
    {
        $hrTsLines = Hr_timesheet_officer::findOrFail($id)->getHrTsLines;
        return new HrTsLineCollection($hrTsLines);
    }
    public function store(Request $request)
    {
        $resource = new HrTsLineStoreResource($request);
        $data = $resource->toArray($request);
        try {
            $timesheetLine = $this->timesheetLineService->create($data);
            return ResponseObject::responseSuccess(
                $timesheetLine,
                [],
                'Created timesheet line successfully'
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }

    public function update(Request $request, $id)
    {
        $resource = new HrTsLineUpdateResource($request);
        $data = $resource->toArray($request);
        $data = array_filter($data, fn ($item) => $item);
        try {
            $this->timesheetLineService->update($id, $data);
            return ResponseObject::responseSuccess(
                null,
                [],
                'Updated timesheet line ' . $id . ' successfully'
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }

    public function destroy($id)
    {
        try {
            $this->timesheetLineService->delete($id);
            return ResponseObject::responseSuccess(
                null,
                [],
                'Deleted timesheet line ' . $id . ' successfully'
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
}
