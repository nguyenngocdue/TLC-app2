<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitSearchable
{
	use TraitFailObject;

	public function searchable(Request $request)
	{
		try {
			$theRows = [
				["a"],
				["b"],
				["c"],
			];
			$message = "";
			return ResponseObject::responseSuccess(
				$theRows,
				[],
				$message,
			);
		} catch (\Throwable $th) {
			return $this->getFailObject($th);
		}
	}
}
