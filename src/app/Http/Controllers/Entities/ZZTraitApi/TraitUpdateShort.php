<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Utils\Support\DateTimeConcern;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitUpdateShort
{
	public function updateShort(Request $request)
	{
		$lines = $request->input('lines');
		// dump($lines);
		$result = [];
		// Log::info("Before loop UpdateShort" . Timer::getTimeElapseFromLastAccess());
		if (!isset($lines)) return ResponseObject::responseFail("Lines not found");

		$ids = [];
		foreach ($lines as $input) $ids[] = $input['id'];

		$allRows = $this->modelPath::whereIn('id', $ids)->get();
		$indexedRows = [];
		foreach ($allRows as $row) $indexedRows[$row->id] = $row;

		foreach ($lines as $input) {
			$id = $input['id'];
			$fieldName = $input['fieldName'];
			$value = $input['value'];

			$theRow = $indexedRows[$id];

			if ($fieldName == 'ot_date') $value = DateTimeConcern::convertForSaving('picker_date', $value);
			$theRow->fill([$fieldName => $value]);
			$result[$id] = $theRow->save();
		}
		$response = ResponseObject::responseSuccess(
			$result,
			$lines,
			"Updated " . sizeof($result) . " lines",
		);
		// session_write_close();
		// Log::info("After loop UpdateShort" . Timer::getTimeElapseFromLastAccess());
		// Log::info($response);
		return $response;
	}
}
