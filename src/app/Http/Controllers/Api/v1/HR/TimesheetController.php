<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Controllers\Controller;
use App\Http\Resources\HrTsLineStoreResource;
use App\Http\Resources\HrTsLineUpdateResource;
use App\Http\Resources\TimesheetLineResource;
use App\Models\Hr_timesheet_line;
use App\Services\Hr_timesheet_line\Hr_timesheet_lineServiceInterface;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;

abstract class TimesheetController extends Controller
{
    public $timesheetLineService;
    public function __construct(Hr_timesheet_lineServiceInterface $timesheetLineService)
    {
        $this->timesheetLineService = $timesheetLineService;
    }
    /**
     * abstract function show return data (all Timesheet line by model Hr_timesheet_officer or Hr_timesheet_worker)
     *
     * @param Request $request
     * @param mixed $id
     * @return void
     */
    abstract protected function show(Request $request, $id);
    /**
     * Create new Timesheet Line document
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $resource = new HrTsLineStoreResource($request);
        $data = $resource->toArray($request);
        try {
            $timesheetLine = $this->timesheetLineService->create($data);
            return new TimesheetLineResource($timesheetLine);
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
    /**
     * Update fields give Timesheet Line by document id
     *
     * @param Request $request
     * @param mixed $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $resource = new HrTsLineUpdateResource($request);
        $data = $resource->toArray($request);
        $data = array_filter($data, fn ($item) => $item);
        try {
            $this->timesheetLineService->update($id, $data);
            return new TimesheetLineResource($this->timesheetLineService->find($id));
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
    /**
     * Delete Timesheet Line by document id
     *
     * @param mixed $id
     * @return void
     */
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

    public function duplicate($id)
    {
        try {
            $timesheetLine = $this->timesheetLineService->duplicate($id);
            return new TimesheetLineResource($timesheetLine);
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
}
