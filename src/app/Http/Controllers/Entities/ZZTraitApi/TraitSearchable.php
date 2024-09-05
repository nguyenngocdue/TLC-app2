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
	private $pageSize = 100;

	public function searchable(Request $request)
	{
		$keyword = $request->keyword;
		$selectingValues = is_string($request->selectingValues) ? explode(',', $request->selectingValues) : $request->selectingValues;

		try {
			$modelPath = Str::modelPathFrom($this->type);
			$sp = SuperProps::getFor($this->type);

			$fields = ["id"];
			foreach ($sp['props'] as $id => $prop) {
				if ($prop['searchable']) {
					$fields[] = substr($prop['name'], 1);
				}
			}

			if ($this->type == 'user') $fields = array_values(array_unique(["id", "name0", ...$fields]));

			// Log::info($sp['props']);
			// Log::info($fields);

			$query = $modelPath::query()
				->select($fields)->whereRaw('1=1');
			if ($selectingValues) {
				// Log::info($selectingValues);
				$query = $query->selectRaw('id in (' . join(",", $selectingValues) . ') as selected')
					->WhereIn('id', $selectingValues);
			} else {
				$query = $query->selectRaw('1 as selected');
			}
			$query = $query->orWhere(function ($query) use ($fields, $keyword) {
				foreach ($fields as $field) {
					$query->orWhere($field, 'LIKE', '%' . $keyword . '%');
				}
			});

			if ($this->type == 'user') $query = $query->where('resigned', 0);

			$query->orderBy('selected', 'desc');
			if (sizeof($fields) > 1) $query->orderBy($fields[1]);
			$message = $query->toSql();
			// Log::info($message);

			$countTotal = $query->count();
			$theRows = $query->limit($this->pageSize)->get();
			return ResponseObject::responseSuccess(
				$theRows,
				[
					'fields' =>	$fields,
					'countTotal' => $countTotal,
				],
				$message,
			);
		} catch (\Throwable $th) {
			return $this->getFailObject($th);
		}
	}
}
