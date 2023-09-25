<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;

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
			$item->{$category} = $newParentId;
			$item->save();
		} catch (\Exception $e) {
			return ResponseObject::responseFail($e->getMessage());
		}
		return ResponseObject::responseSuccess([], [], "Updated");
	}
}
