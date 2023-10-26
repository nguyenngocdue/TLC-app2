<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Models\Kanban_task_group;
use App\Models\Kanban_task_page;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperProps;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitKanban
{
	use TraitKanbanItemRenderer;
	use TraitKanbanUpdate;
	use TraitKanbanTransition;
	use TraitKanbanWsResponse;

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

		$parentCountingType = null;
		if ($table == 'kanban_tasks') {
			$newTransitionId = $this->setTransitionLog($item, $newParentId);
			$item->kanban_task_transition_id = $newTransitionId;

			$parent = Kanban_task_group::find($newParentId);
			$parentCountingType = $parent->time_counting_type;
			$parentPreviousGroupId = $parent->previous_group_id;
			$parentRectifiedGroupId = $parent->rectified_group_id;
		}

		$item->save();
		$meta = [
			'id' => $itemId,
			'table' => $table,
			'newParentId' => $newParentId,
			'parentCountingType' => $parentCountingType,

		];
		if ($table == 'kanban_tasks') {
			$meta += 	[
				'parentPreviousGroupId' => $parentPreviousGroupId,
				'parentRectifiedGroupId' => $parentRectifiedGroupId,
			];
		}
		return ResponseObject::responseSuccess([], $meta, "Updated");
	}

	function changeOrder(Request $request)
	{
		$orders = $request->input('order');
		// $table = $this->modelPath::getTableName();
		// Log::info($table);
		$orders = $this->getLastWord($orders);
		// Log::info($orders);
		// Log::info($this->modelPath);
		foreach ($orders as $index => $order) {
			$item = $this->modelPath::find($order);
			$item->order_no = $index;
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
			case "kanban_task_groups":
				return "kanban_cluster_id";
			case "kanban_task_clusters":
				return "kanban_page_id";
			case "kanban_task_pages":
				return "kanban_bucket_id";
			case "kanban_task_buckets":
				return "";
			default:
				throw new \Exception("Unknown parent column of $table.");
		}
	}

	function addANewItem(Request $request)
	{
		$table = $this->modelPath::getTableName();
		$parent_column = $this->getParentColumn($table);
		$parent_id =  $request->input('parent_id');
		$groupWidth = $request->input('groupWidth');
		$cuid = CurrentUser::id();

		$item = [
			'name' => "New Item",
			$parent_column => $parent_id,
			'owner_id' => $cuid,
		];
		// Log::info($item);
		$insertedObj = $this->modelPath::create($item);

		$insertedId = $insertedObj->id;
		$insertedObj->order_no = $insertedId;

		if ($table === 'kanban_tasks') {
			$transitionId = $this->enteringGroup($insertedObj, $parent_id);
			$insertedObj->kanban_task_transition_id = $transitionId;
		}

		$insertedObj->save();

		$table = ($table == 'kanban_task_pages') ? 'kanban_task_tocs' : $table;
		$renderer = $this->renderKanbanItem($table, $insertedObj, $groupWidth);
		return ResponseObject::responseSuccess(['renderer' => $renderer], ['id' => $insertedId, 'item' => $insertedObj], "Inserted");
	}

	private function saveUserSettings($pageId)
	{
		$user = CurrentUser::get();
		// Log::info($user->settings);
		$settings = $user->settings;
		$settings[$this->type][Constant::VIEW_ALL]['current_page'] = $pageId;
		$user->settings = $settings;
		$user->save();
	}

	function loadKanbanPage(Request $request)
	{
		$id = $request->input('pageId');
		$groupWidth = $request->input('groupWidth');

		$page = Kanban_task_page::find($id);
		$renderer = Blade::render('<x-renderer.kanban.page :page="$page" groupWidth="{{$groupWidth}}"/>', [
			'page' => $page,
			'groupWidth' => $groupWidth,
		]);
		$this->saveUserSettings($id);

		return ResponseObject::responseSuccess(['renderer' => $renderer], [], "Inserted");
	}

	function editItemRenderProps(Request $request)
	{
		$input = $request->input();
		['id' => $id] = $input;

		$item = $this->modelPath::find($id);
		$table = $this->modelPath::getTableName();
		$props = SuperProps::getFor($table)['props'];

		$component = '<x-renderer.kanban.item-renderer-modal 
			id="{{$id}}"
			type="{{$type}}"
			modelPath="{{$modelPath}}"

			:item="$item" 
			:props="$props" 
			/>';
		$renderer = Blade::render($component, [
			'id' => $id,
			'item' => $item,
			'type' => $table,
			'props' => $props,
			'modelPath' => $this->modelPath,
		]);
		// $renderer = json_encode($item);
		return ResponseObject::responseSuccess(['renderer' => $renderer], [
			'input' => $input,
			'props' => $props,
			'item' => $item,
		], "Rendered");
	}

	function deleteItemRenderProps(Request $request)
	{
		$input = $request->input();
		['id' => $id] = $input;

		$this->modelPath::destroy([$id]);

		return ResponseObject::responseSuccess([], [], "Deleted");
	}

	function reRenderKanbanItem(Request $request)
	{
		$table = $request->input('table');
		$id = $request->input('id');
		$guiType = $request->input('guiType');

		$modelPath = Str::modelPathFrom($table);
		$item = $modelPath::find($id);

		if ($guiType !== 'cardPage') {
			if ($table === 'kanban_task_pages') {
				$table = 'kanban_task_tocs'; //<< This is a fake table
			}
		}
		$renderer = $this->renderKanbanItem($table, $item);

		return ResponseObject::responseSuccess(['renderer' => $renderer, 'item' => $item, 'modelPath' => $modelPath], $request->input(), "Re-render");
	}

	function kanban(Request $request)
	{
		try {
			$action = $request->input('action');
			switch ($action) {
				case "reRenderKanbanItem":
					return $this->{$action}($request);
				case "changeOrder":
				case "changeParent":
				case "changeName":
				case "addANewItem":
				case "updateItemRenderProps":
				case "deleteItemRenderProps":

				case "loadKanbanPage":
				case "editItemRenderProps":
					$result = $this->{$action}($request);

					$this->kanbanBroadcast($request);
					return $result;
			}
		} catch (\Exception $e) {
			return ResponseObject::responseFail($e->getMessage() . " Line " . $e->getLine());
		}
		return ResponseObject::responseFail("Unknown kanban action " . $action);
	}
}
