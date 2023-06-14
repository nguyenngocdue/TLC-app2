<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\Json\SuperProps;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitStoreEmpty
{
	public function storeEmpty(Request $request)
	{
		$sp = SuperProps::getFor($this->type);
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
			$theRows[] = $this->modelPath::create($item);
		}
		return ResponseObject::responseSuccess(
			$theRows,
			['defaultValue' => $defaultValue, 'requestedLines' => $lines],
			"Created " . sizeof($theRows) . " lines",
		);
	}
}
