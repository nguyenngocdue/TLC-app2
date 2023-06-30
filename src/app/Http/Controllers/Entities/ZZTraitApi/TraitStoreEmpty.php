<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Models\Hr_timesheet_line;
use App\Models\Hr_timesheet_worker;
use App\Models\User_team_tsht;
use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\Json\SuperProps;
use App\Utils\System\Api\ResponseObject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitStoreEmpty
{
	public function tso_week_validate($lines)
	{
		foreach ($lines as $value) {
			if ($week = $value['week']) {
				if ($week) {
					$date = Carbon::parse($week);
					$dateStartOfWeek = Carbon::parse($week)->startOfWeek()->format('Y-m-d');
					if ($dateStartOfWeek == $week) {
						return true;
					} else {
						if ($date->day == 26) {
							return true;
						} else {
							return false;
						}
					}
				}
				return false;
			}
		}
	}

	public function storeEmpty(Request $request)
	{
		// try{
		$sp = SuperProps::getFor($this->type);
		$props = $sp['props'];
		$lines = $request->get('lines');
		$validation = true;
		switch ($this->type) {
			case 'hr_timesheet_officer':
				$validation = $this->tso_week_validate($lines);
				break;
			default:
				break;
		}

		if ($validation === false) return ResponseObject::responseFail("Validation failed");
		if (is_null($lines)) return ResponseObject::responseFail("Lines is null.");
		$theRows = [];
		$defaultValue = $this->getDefaultValue($props);
		foreach ($lines as $item) {
			// Log::info($defaultValue);
			foreach ($defaultValue as $key => $value) {
				// Order_no get override when without $value !== false
				if (isset($item[$key]) && $item[$key] !== false && $value !== false) $item[$key]  = $value;
				// if (isset($item[$key]) && $item[$key] !== false) $item[$key]  = $value;
			}
			// Log::info($item);
			if (isset($item['ot_date'])) {
				$item['ot_date'] = DateTimeConcern::convertForSaving('picker_date', $item['ot_date']);
			}
			$item = $this->applyFormula($item, 'store');
			$createdItem = $this->modelPath::create($item);
			$tableName = Str::plural($this->type);
			$createdItem->redirect_edit_href = route($tableName . '.edit', $createdItem->id);
			$theRows[] = $createdItem;
		}

		$totalInsertedRows = 0;
		switch ($this->type) {
			case 'hr_timesheet_worker':
				foreach ($theRows as $row) {
					$team = User_team_tsht::find($row->team_id);
					$workers = $team->getTshtMembers();
					$totalInsertedRows  += count($workers);
					foreach ($workers as $worker) {
						Hr_timesheet_line::create([
							'timesheetable_type' => Hr_timesheet_worker::class,
							'timesheetable_id' => $row->id,
							'owner_id' => $row->owner_id,
							'user_id' => $worker->id,
							'discipline_id' => $worker->discipline,
							'task_id' => 357, //357 = on the floor
							'work_mode_id' => 2, //2 = office/workshop
							'duration' => 480, //480 = 8 * 60
						]);
					}
				}

				break;
			default:
				break;
		}
		// } catch (\Exception $e) {
		// 	return ResponseObject::responseFail($e->getMessage());
		// }

		$message = "Created " . sizeof($theRows) . " " . Str::plural("line", sizeof($theRows)) . ".";
		if ($totalInsertedRows > 0) {
			$message = "Created "
				. sizeof($theRows)
				. " "
				. Str::plural("document", sizeof($theRows))
				. " with "
				. $totalInsertedRows
				. " "
				. Str::plural("line", $totalInsertedRows)
				. ".";
		}

		return ResponseObject::responseSuccess(
			$theRows,
			['defaultValue' => $defaultValue, 'requestedLines' => $lines],
			$message,
		);
	}
}
