<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFieldHandler2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitSendNotificationAndMail;
use App\Models\Hr_timesheet_line;
use App\Models\Hr_timesheet_worker;
use App\Models\Priority;
use App\Models\Site_daily_assignment_line;
use App\Models\User_team_site;
use App\Models\User_team_tsht;
use App\Utils\Constant;
use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\Json\SuperProps;
use App\Utils\System\Api\ResponseObject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


trait TraitStoreEmpty
{
	use TraitSendNotificationAndMail;
	use TraitEntityFieldHandler2;

	private function getModelButNeedToHaveAMoreDecentWay($fieldName, $sp)
	{
		$model = ($sp['props']["_" . $fieldName . "()"]['relationships']['oracyParams'][1]);
		return $model;
	}

	private function insertOracy($item, $createdItem, $sp)
	{
		// Log::info($item);
		$oracy = array_filter($item, fn ($fnName) => str_contains($fnName, '()'), ARRAY_FILTER_USE_KEY);
		// Log::info($oracy);
		foreach ($oracy as $functionName => $valueArr) {
			$fieldName = substr($functionName, 0, strlen($functionName) - 2);
			$model = $this->getModelButNeedToHaveAMoreDecentWay($fieldName, $sp);
			$createdItem->attachCheck($fieldName, $model, $valueArr);
		}
	}

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
		try {
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

			$sp = SuperProps::getFor($this->type);
			// $props = $sp['props'];
			$dateTimeControls = $sp['datetime_controls'];
			// Log::info($dateTimeControls);
			$numberControls = $sp['number_controls'];
			// Log::info($numberControls);

			$theRows = [];
			$defaultValue = $this->getDefaultValue($props);
			foreach ($lines as $item) {
				// Log::info($defaultValue);
				foreach ($defaultValue as $key => $value) {
					// Log::info($item[$key] . " " . $key . " " . $value);
					// Order_no get override when without $value !== false
					// if (isset($item[$key]) && $item[$key] !== false && $value !== false) $item[$key]  = $value;
					//Default of ot_date = "0", break_time = 0 will be filtered out with $value !== false
					// if (isset($item[$key]) && $item[$key] !== false) $item[$key]  = $value;
					//<<Only if default values IS NOT FALSE: parent_id, order_no
					if ($value !== false) {
						$item[$key] = $value;
					}
				}
				// Log::info($item);
				foreach ($dateTimeControls as $control => $controlType) {
					// $control = substr($control, 1); // Removed first _
					if (isset($item[$control])) {
						//0 is for now()
						if ($item[$control] || ($item[$control] == 0)) {
							//<< 0: now, +7 days...
							$adding = ($item[$control] == 0) ? strtotime(now()) : strtotime($item[$control]);
							switch ($controlType) {
								case "picker_date":
									$item[$control] = date(Constant::FORMAT_DATE_MYSQL, $adding);
									break;
								case "picker_datetime":
									$item[$control] = date(Constant::FORMAT_DATETIME_MYSQL, $adding);
									break;
									//Current time, other adding have not been implemented
								case "picker_time":
									$currentTime = Carbon::now(DateTimeConcern::getTz());
									$item[$control] = $currentTime->format(Constant::FORMAT_TIME_MYSQL);
									// $item[$control] = DateTimeConcern::formatForSaving($currentTime, Constant::FORMAT_TIME_MYSQL, Constant::FORMAT_TIME_MYSQL);
									break;
								case "picker_week":
									break;
								default:
									dump("Unknown how to convert $controlType 777888");
									break;
							}
						}
						$item[$control] = DateTimeConcern::convertForSaving($controlType, $item[$control]);
					}
				}
				foreach ($numberControls as $control => $controlType) {
					if (isset($item[$control])) {
						$item[$control] = Str::replace(',', '', $item[$control]);
					}
				}
				$item = $this->applyFormula($item, 'store');
				// Log::info($item);

				$createdItem = $this->modelPath::create($item);

				$this->insertOracy($item, $createdItem, $sp);
				// Log::info("Store empty");
				// Log::info($createdItem);

				$this->eventCreatedNotificationAndMail($createdItem->getAttributes(), $createdItem->id, 'new', []);
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
						foreach ($workers as $worker) {
							Hr_timesheet_line::create([
								'timesheetable_type' => Hr_timesheet_worker::class,
								'timesheetable_id' => $row->id,
								'owner_id' => $row->owner_id,
								'user_id' => $worker->id,
								'discipline_id' => $worker->discipline,
								'task_id' => 357, //357 = on the floor
								'work_mode_id' => 2, //2 = office/workshop
								'duration_in_hour' => 8,
								'duration_in_min' => 480, //480 = 8 * 60
							]);
						}
						$totalInsertedRows  += count($workers);
					}
					break;
				case "site_daily_assignment":
					foreach ($theRows as $row) {
						$team = User_team_site::find($row->site_team_id);
						$workers = $team->getSiteMembers();
						foreach ($workers as $worker) {
							Site_daily_assignment_line::create([
								'owner_id' => $row->owner_id,
								'user_id' => $worker->id,
								'employeeid' => $worker->employeeid,
								'site_daily_assignment_id' => $createdItem->id,
							]);
						}
						$totalInsertedRows  += count($workers);
					}
					break;
				default:
					break;
			}

			$meta = $request->input('meta');
			$caller = $meta['caller'] ?? "";
			$line_or_doc = (in_array($caller, ['view-all-matrix', 'view-all-calendar'])) ? "document" : "line";
			$message = "Created " . sizeof($theRows) . " " . Str::plural($line_or_doc, sizeof($theRows)) . ".";
			return ResponseObject::responseSuccess(
				$theRows,
				['defaultValue' => $defaultValue, 'requestedLines' => $lines],
				$message,
			);
		} catch (\Throwable $th) {
			$message = $th->getMessage();
			if (str_contains($message, 'Integrity constraint violation: 1062 Duplicate entry'))
				$message = "This document is exist, please refresh the page to load the latest matrix.";
			return ResponseObject::responseFail(
				$message,
			);
		}
	}
}
