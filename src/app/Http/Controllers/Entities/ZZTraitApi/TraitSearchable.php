<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Utils\Support\Erp;
use App\Utils\Support\Json\SuperProps;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

trait TraitSearchable
{
	use TraitFailObject;
	private $pageSize = 100;

	// private function searchOnExternalTable($sp, $keyword)
	// {
	// 	$modelPath = Erp::getModelPath($this->type);
	// 	if (!$modelPath) return [[], 0, []];

	// 	$selectRaws = [];
	// 	$whereRaws = [];
	// 	foreach ($sp['props'] as $prop) {
	// 		if (!$prop['searchable']) continue;
	// 		if (isset($prop['external_column']) && $prop['external_column']) {
	// 			$selectRaws[] = '"' . $prop['external_column'] . '" as ' . $prop['column_name'];
	// 			if ($keyword) {
	// 				$whereRaws[] = 'CAST("' . $prop['external_column'] . '" as VARCHAR) COLLATE SQL_Latin1_General_CP1_CI_AI LIKE \'%' . $keyword . '%\'';
	// 			}
	// 		}
	// 	}
	// 	// Log::info($selectRaws);
	// 	// Log::info($whereRaws);

	// 	$query = $modelPath::query()
	// 		->selectRaw(join(',', $selectRaws));
	// 	foreach ($whereRaws as $whereRaw) {
	// 		$query = $query->orWhereRaw($whereRaw);
	// 	}

	// 	$query = $query->limit(100);
	// 	$result = $query->get();
	// 	$result->each(function ($item) {
	// 		unset($item->timestamp);
	// 	});

	// 	$columns = Erp::getAllColumns($this->type);
	// 	$totalCount = $query->count();
	// 	// dump($result);

	// 	return [$result, $totalCount, $query->toSql(), $columns];
	// }

	private function searchOnLocalTable($modelPath, $fields, $keyword, $selectingValues)
	{
		$fieldNames = array_map(fn($field) => $field['name'], $fields);
		$query = $modelPath::query()
			->select($fieldNames); //->whereRaw('1=1');
		if ($selectingValues) {
			// Log::info($selectingValues);
			$query = $query->selectRaw('id in (' . join(",", $selectingValues) . ') as selected')
				->WhereIn('id', $selectingValues);
		} else {
			$query = $query->selectRaw('1 as selected');
		}
		if ($keyword) {
			$query = $query->orWhere(function ($query) use ($fieldNames, $keyword) {
				foreach ($fieldNames as $field) {
					$query->orWhere($field, 'LIKE', '%' . $keyword . '%');
				}
			});
		} else {
			$query = $query->orWhereRaw('1=1');
		}

		if ($this->type == 'user') $query = $query->where('resigned', 0);

		$query->orderBy('selected', 'desc');
		if (sizeof($fieldNames) > 1) $query->orderBy($fieldNames[1]);
		$sql = $query->toSql();
		// Log::info($message);

		$countTotal = $query->count();
		$theRows = $query->limit($this->pageSize)->get();
		return [$theRows, $countTotal, $sql];
	}

	private function getFieldListFromProp($sp)
	{
		$fields = [];
		foreach ($sp['props'] as $id => $prop) {

			if ($prop['searchable'] || $prop['name'] == '_id') {
				$field = [
					'name' => substr($prop['name'], 1),
					'label' => $prop['label'],
					'width' => ($prop['width'] ?? 100),
				];
				$fields[] = $field;
			}
		}
		//move the first item to last
		$fields = array_merge(array_slice($fields, 1), array_slice($fields, 0, 1));

		return $fields;
	}

	public function searchable(Request $request)
	{
		$keyword = $request->keyword;
		$selectingValues = is_string($request->selectingValues) ? explode(',', $request->selectingValues) : $request->selectingValues;

		try {
			$modelPath = Str::modelPathFrom($this->type);
			$sp = SuperProps::getFor($this->type);

			$fields = $this->getFieldListFromProp($sp);

			[$theRows, $countTotal, $sql1] = $this->searchOnLocalTable($modelPath, $fields, $keyword, $selectingValues);
			// [$moreRows, $moreTotal, $sql2, $columns] = $this->searchOnExternalTable($sp, $keyword);

			// $theRows = $theRows->concat($moreRows);

			return ResponseObject::responseSuccess($theRows, [
				'fields' =>	$fields,
				'countTotal1' => $countTotal,
				'sql1' => $sql1,
				// 'columns2' => $columns,
				// 'hits2' => $moreRows,
				// 'countTotal2' => $moreTotal,
				// 'sql2' => $sql2,
			]);
		} catch (\Throwable $th) {
			Log::error($th);
			return $this->getFailObject($th);
		}
	}
}
