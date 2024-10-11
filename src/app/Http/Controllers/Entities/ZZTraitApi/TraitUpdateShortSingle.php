<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFieldHandler2;
use App\Utils\Support\Json\SuperProps;
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

			$object = $this->modelPath::find($params['id']);
			$inserted = $object->update($params);

			$props = SuperProps::getFor($this->modelPath::getTableName())['props'];
			$belongsToMany = array_filter($props, function ($prop) {
				return $prop['column_type'] === 'belongsToMany';
			});

			foreach ($belongsToMany as $value) {
				$column_name = $value['column_name'];
				// Log::info($column_name);
				// Log::info(array_keys($params));
				if (in_array($column_name, array_keys($params))) {
					// Log::info("===>" . $column_name . " " . $params[$column_name]);
					// Log::info($params[$column_name]);
					if ($params[$column_name] === null) {
						$object->$column_name()->detach();
						continue;
					} else {
						// $ids = explode(',', $params[$column_name]);
						$object->$column_name()
							->syncWithPivotValues(
								$params[$column_name],
								['owner_id' => 1]
							);
					}
				}
			}

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
