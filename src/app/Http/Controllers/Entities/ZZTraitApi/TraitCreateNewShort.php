<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFieldHandler2;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;

trait TraitCreateNewShort
{
	use TraitEntityFieldHandler2;
	use TraitFailObject;

	public function createNewShort(Request $request)
	{
		try {
			$params = $request->input();
			$message = 'Create new short successfully';

			$inserted = $this->modelPath::create($params);

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
