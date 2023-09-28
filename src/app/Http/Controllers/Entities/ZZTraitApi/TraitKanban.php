<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Utils\Support\CurrentUser;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
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
		return ResponseObject::responseSuccess([], [], "Updated");
	}

	function changeOrder(Request $request)
	{
		$orders = $request->input('order');
		// $table = $this->modelPath::getTableName();
		// Log::info($table);
		$orders = $this->getLastWord($orders);
		// Log::info($orders);
		foreach ($orders as $index => $order) {
			$item = $this->modelPath::find($order);
			$item->order_no = $index * 10;
			$item->save();
		}
		return ResponseObject::responseSuccess($orders, [], "Updated");
	}

	function changeName(Request $request)
	{
		// Log::info($request->input());
		$id = $request->input('id');
		$newName = $request->input('newText');
		$item = $this->modelPath::find($id);
		$item->name = $newName;
		// Log::info($item . " " . $newName);
		$item->save();
		return ResponseObject::responseSuccess($newName, [], "Updated");
	}

	function getParentColumn($table)
	{
		switch ($table) {
			case "kanban_tasks":
				return "kanban_group_id";
		}
	}

	function addNew(Request $request)
	{
		$table = $this->modelPath::getTableName();
		$parent_column = $this->getParentColumn($table);
		$groupWidth = $request->input('groupWidth');

		$insertedObj = $this->modelPath::create([
			'name' => "New Item",
			$parent_column => $request->input('parent_id'),
			'owner_id' => CurrentUser::id(),
		]);
		$insertedId = $insertedObj->id;

		$renderer = "";
		switch ($table) {
			case 'kanban_tasks':
				$renderer = Blade::render('<x-renderer.kanban.task :task="$task" groupWidth="{{$groupWidth}}"/>', [
					'task' => $insertedObj,
					'groupWidth' => $groupWidth,
				]);
				break;
			case 'kanban_task_groups':
				$renderer = Blade::render('<x-renderer.kanban.group :group="$group" groupWidth="{{$groupWidth}}"/>', [
					'group' => $insertedObj,
					'groupWidth' => $groupWidth,
				]);
				break;
			case 'kanban_task_clusters':
				$renderer = Blade::render('<x-renderer.kanban.cluster :cluster="$cluster" groupWidth="{{$groupWidth}}"/>', [
					'cluster' => $insertedObj,
					'groupWidth' => $groupWidth,
				]);
				break;
			default:
				$renderer = "Unknown how to render kanban for $table";
				break;
		}

		return ResponseObject::responseSuccess(['renderer' => $renderer], ['id' => $insertedId], "Inserted");
	}

	function kanban(Request $request)
	{
		try {
			$action = $request->input('action');
			switch ($action) {
				case "changeOrder":
				case "changeParent":
				case "changeName":
				case "addNew":
					return $this->{$action}($request);
					break;
			}
		} catch (\Exception $e) {
			return ResponseObject::responseFail($e->getMessage() . " Line " . $e->getLine());
		}
		return ResponseObject::responseFail("Unknown kanban action " . $action);
	}
}
