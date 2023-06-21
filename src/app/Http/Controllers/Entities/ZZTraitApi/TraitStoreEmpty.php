<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

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

		if ($validation === false) return ResponseObject::responseFail();
		$theRows = [];
		$defaultValue = $this->getDefaultValue($props);
		foreach ($lines as $item) {
			foreach ($defaultValue as $key => $value) {
				if (isset($item[$key]) && $item[$key] !== false) $item[$key]  = $value;
			}
			if (isset($item['ot_date'])) {
				$item['ot_date'] = DateTimeConcern::convertForSaving('picker_date', $item['ot_date']);
			}
			$item = $this->applyFormula($item, 'store');
			$createdItem = $this->modelPath::create($item);
			$tableName = Str::plural($this->type);
			$createdItem->redirect_edit_href = route($tableName . '.edit', $createdItem->id);
			$theRows[] = $createdItem;
		}
		return ResponseObject::responseSuccess(
			$theRows,
			['defaultValue' => $defaultValue, 'requestedLines' => $lines],
			"Created " . sizeof($theRows) . " lines",
		);
	}
}
