<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Utils\Support\Json\SuperProps;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

trait TraitSearchable
{
	use TraitFailObject;

	public function searchable(Request $request)
	{
		$keyword = $request->keyword;
		// Log::info($request->all());
		// Log::info($this->type);
		// Log::info($keyword);
		try {
			$modelPath = Str::modelPathFrom($this->type);
			$sp = SuperProps::getFor($this->type);

			$fields = ["id"];
			foreach ($sp['props'] as $id => $prop) {
				if ($prop['searchable']) {
					$fields[] = substr($prop['name'], 1);
				}
			}
			// Log::info($sp['props']);
			// Log::info($fields);

			$query = $modelPath::query()
				->select($fields)
				->where(function ($query) use ($fields, $keyword) {
					foreach ($fields as $field) {
						$query->orWhere($field, 'LIKE', '%' . $keyword . '%');
					}
				});
			$message = $query->toSql();

			$theRows = $query->get();
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
