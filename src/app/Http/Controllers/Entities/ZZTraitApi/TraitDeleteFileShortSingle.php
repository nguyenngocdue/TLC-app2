<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFieldHandler2;
use App\Http\Services\UploadService2;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitDeleteFileShortSingle
{
	use TraitEntityFieldHandler2;
	use TraitFailObject;

	public function deleteFileShortSingle(Request $request)
	{
		try {
			$params = $request->input();
			$id = $params['id'];
			$message = 'Short Deleted successfully';

			$uploadService2 = new UploadService2($this->modelPath);
			$deleted = $uploadService2->destroy([$id], true);

			return ResponseObject::responseSuccess(
				$deleted,
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
