<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFieldHandler2;
use App\Http\Services\UploadService2;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitUploadShortSingle
{
	use TraitEntityFieldHandler2;
	use TraitFailObject;

	public function uploadShortSingle(Request $request)
	{
		try {
			$params = $request->input();
			$id = $params['id'];
			$message = 'Short Upload successfully';

			$uploadService2 = new UploadService2($this->modelPath);
			$inserted = $uploadService2->store($request, $this->modelPath, $id);

			return ResponseObject::responseSuccess(
				$inserted,
				[
					$params,
				],
				$message,
			);
		} catch (\Throwable $th) {
			return $this->getFailObject($th);
		}
	}
}
