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

	private function cleanString($string)
	{
		// Use preg_replace to remove \u0000 and * characters
		return preg_replace('/\x00|\*/', '', $string);
	}

	private function searchOnExternalTable($modelPath, $keyword)
	{
		$modelPath = $modelPath . "_external";
		if (!class_exists($modelPath)) return [[], 0, []];

		$query = $modelPath::query()
			->selectRaw('Name as name, Address as address, "VAT Registration No_" as reg_no')

			->orWhereRaw('Name COLLATE SQL_Latin1_General_CP1_CI_AI LIKE \'%' . $keyword . '%\'')
			->orWhereRaw('Address COLLATE SQL_Latin1_General_CP1_CI_AI LIKE \'%' . $keyword . '%\'')
			->orWhereRaw(' CAST("VAT Registration No_" as VARCHAR) LIKE \'%' . $keyword . '%\'');
		$query = $query->limit(100);
		$result = $query->get();
		$result->each(function ($item) {
			unset($item->timestamp);
		});

		// Log::info((array)$result->first());

		$columns = $result->count() > 0 ? array_keys((array)$result->first()->getOriginal()) : [];
		$columns = array_map(fn($col) => $this->cleanString($col), $columns);
		$totalCount = $query->count();
		// dump($result);

		return [$result, $totalCount, $columns];
	}

	private function searchOnLocalTable($modelPath, $fields, $keyword, $selectingValues)
	{
		$fieldNames = array_map(fn($field) => $field['name'], $fields);
		$query = $modelPath::query()
			->select($fieldNames)->whereRaw('1=1');
		if ($selectingValues) {
			// Log::info($selectingValues);
			$query = $query->selectRaw('id in (' . join(",", $selectingValues) . ') as selected')
				->WhereIn('id', $selectingValues);
		} else {
			$query = $query->selectRaw('1 as selected');
		}
		$query = $query->orWhere(function ($query) use ($fieldNames, $keyword) {
			foreach ($fieldNames as $field) {
				$query->orWhere($field, 'LIKE', '%' . $keyword . '%');
			}
		});

		if ($this->type == 'user') $query = $query->where('resigned', 0);

		$query->orderBy('selected', 'desc');
		if (sizeof($fieldNames) > 1) $query->orderBy($fieldNames[1]);
		$message = $query->toSql();
		// Log::info($message);

		$countTotal = $query->count();
		$theRows = $query->limit($this->pageSize)->get();
		return [$theRows, $countTotal, $message];
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

			[$theRows, $countTotal, $message] = $this->searchOnLocalTable($modelPath, $fields, $keyword, $selectingValues);
			[$moreRows, $moreTotal, $columns] = $this->searchOnExternalTable($modelPath, $keyword);

			$theRows = $theRows->concat($moreRows);

			return ResponseObject::responseSuccess($theRows, [
				'fields' =>	$fields,
				'columns' => $columns,
				'countTotal' => $countTotal + $moreTotal,
			], $message,);
		} catch (\Throwable $th) {
			Log::error($th);
			return $this->getFailObject($th);
		}
	}
}
