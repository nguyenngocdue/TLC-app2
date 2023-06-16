<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\Json\SuperProps;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitStoreEmpty
{
	public function tso_week_validate()
	{
		return false;
	}

	public function storeEmpty(Request $request)
	{
		$sp = SuperProps::getFor($this->type);

		$validation = true;
		switch ($this->type) {
			case 'hr_timesheet_officer':
				$validation = $this->tso_week_validate();
				break;
			default:
				break;
		}

		if ($validation === false) return ResponseObject::responseFail();

		$props = $sp['props'];
		$lines = $request->get('lines');
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
