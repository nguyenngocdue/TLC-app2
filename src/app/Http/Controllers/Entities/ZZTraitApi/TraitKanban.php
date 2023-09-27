<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitKanban
{
	function getLastWord($strings)
	{
		$characters_after_last_underscore = [];
		if (is_null($strings)) return [];
		foreach ($strings as $string) {
			$last_underscore_position = strrpos($string, '_');

			if ($last_underscore_position !== false) {
				$characters_after_last_underscore[] = substr($string, $last_underscore_position + 1);
			} else {
				$characters_after_last_underscore[] = $string; // If no underscore is found, keep the whole string
			}
		}
		return $characters_after_last_underscore;
	}

	function changeParent(Request $request)
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
			if (!isset($item->{$category})) {
				Log::info($this->modelPath . " [" . $itemId . ']' . "->" . $category);
				Log::info($item);
				throw new \Exception("Category '$category' not found in '$table'.");
			}
			$item->{$category} = $newParentId;
			$item->save();
		} catch (\Exception $e) {
			return ResponseObject::responseFail($e->getMessage() . " Line " . $e->getLine());
		}
		return ResponseObject::responseSuccess([], [], "Updated");
	}

	function changeOrder(Request $request)
	{
		try {
			$orders = $request->input('order');
			// $table = $this->modelPath::getTableName();
			// Log::info($table);
			$orders = $this->getLastWord($orders);
			// Log::info($orders);
			foreach ($orders as $index => $order) {
				$item = $this->modelPath::find($order);
				$item->order_no = $index * 100;
				$item->save();
			}
		} catch (\Exception $e) {
			return ResponseObject::responseFail($e->getMessage() . " Line " . $e->getLine());
		}
		return ResponseObject::responseSuccess($orders, [], "Updated");
	}

	function changeName(Request $request)
	{
		try {
			// Log::info($request->input());
			$id = $request->input('id');
			$newName = $request->input('newText');
			$item = $this->modelPath::find($id);
			$item->name = $newName;
			// Log::info($item . " " . $newName);
			$item->save();
		} catch (\Exception $e) {
			return ResponseObject::responseFail($e->getMessage() . " Line " . $e->getLine());
		}
		return ResponseObject::responseSuccess($newName, [], "Updated");
	}

	function kanban(Request $request)
	{
		$action = $request->input('action');
		switch ($action) {
			case "changeOrder":
			case "changeParent":
			case "changeName":
				return $this->{$action}($request);
				break;
		}
		// throw new \Exception("Unknown kanban action " . $action);
		return ResponseObject::responseFail("Unknown kanban action " . $action);
	}
}
