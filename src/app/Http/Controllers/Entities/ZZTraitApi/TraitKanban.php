<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitKanban
{
	function kanban(Request $request)
	{
		try {
			$input = $request->input();
			[
				'category' => $category,
				'itemId' => $itemId,
				'newParentId' => $newParentId,
			] = $input;
			$item = $this->modelPath::find($itemId);
			$table = $this->modelPath::getTableName();
			// Log::info($this->modelPath . " [" . $itemId . ']');
			// Log::info($item);
			if (!isset($item->{$category})) {
				throw new \Exception("Category '$category' not found in '$table'.");
			}
			$item->{$category} = $newParentId;
			$item->save();
		} catch (\Exception $e) {
			return ResponseObject::responseFail($e->getMessage() . " Line " . $e->getLine());
		}
		return ResponseObject::responseSuccess([], [], "Updated");
	}
}
