<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFieldHandler2;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitUpdateShortSingle
{
	use TraitEntityFieldHandler2;
	use TraitFailObject;

	public function updateShortSingle(Request $request)
	{
		try {
			$params = $request->input();
			$message = 'Short Updated successfully';

			$inserted = $this->modelPath::find($params['id'])->update($params);

			return ResponseObject::responseSuccess(
				$inserted,
				[$params],
				$message,
			);
		} catch (\Throwable $th) {
			return $this->getFailObject($th);
		}
	}
}
