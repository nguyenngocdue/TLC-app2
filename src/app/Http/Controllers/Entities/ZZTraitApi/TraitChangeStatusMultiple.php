<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitChangeStatusMultiple
{
	public function changeStatusMultiple(Request $request)
	{
		$type = $this->type;
		// dump($request->input());
		$ids = $request->input('ids');
		$nextStatus = $request->input('nextStatus');
		$nextStatusLabel = $request->input('nextStatusLabel');
		// dump($ids, $type);
		try {
			$modelPath = Str::modelPathFrom($type);
			if (is_array($ids) && !empty($ids)) {
				$modelPath::whereIn('id', $ids)
					->update(['status' => $nextStatus]);
				$count = count($ids);
				$itemStr = Str::plural('item', $count);
				return ResponseObject::responseSuccess(
					null,
					[$ids],
					"Changed to <b>$nextStatusLabel</b> for $count $itemStr successfully!",
				);
			}
		} catch (\Throwable $th) {
			return ResponseObject::responseFail(
				$th->getMessage(),
			);
		}
	}
}
