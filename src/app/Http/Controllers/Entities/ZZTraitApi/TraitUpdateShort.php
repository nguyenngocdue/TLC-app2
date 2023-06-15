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
		foreach ($lines as $input) {
			$id = $input['id'];
			$fieldName = $input['fieldName'];
			$value = $input['value'];

			$theRow = $this->modelPath::find($id);
			if ($fieldName == 'ot_date') $value = DateTimeConcern::convertForSaving('picker_date', $value);
			$theRow->fill([$fieldName => $value]);
			$result[$id] = $theRow->save();
		}
		return ResponseObject::responseSuccess(
			$result,
			$lines,
			"Updated " . sizeof($result) . " lines",
		);
	}
}
