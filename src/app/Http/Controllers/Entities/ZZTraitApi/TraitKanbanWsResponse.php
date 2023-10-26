<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Events\WssKanbanChannel;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


trait TraitKanbanWsResponse
{
	use TraitKanbanItemRenderer;

	function getParentTable($table)
	{
		switch ($table) {
			case "kanban_tasks":
				return "kanban_task_groups";
			case "kanban_task_groups":
				return "kanban_task_clusters";
			case "kanban_task_clusters":
				return "kanban_task_pages";
			case "kanban_task_pages":
				return "kanban_task_buckets";
			case "kanban_task_buckets":
				return "all_buckets";
			default:
				throw new \Exception("Unknown parent table of $table.");
		}
	}

	function kanbanBroadcast(Request $request)
	{
		$action = $request->input('action');

		$tableName = $this->modelPath::getTableName();
		// $wsResponse = new WssKanbanChannel($request->input() + ['tableName' => $tableName]);
		try {
			$action = $request->input('action');
			$tableName = $this->modelPath::getTableName();
			$params = $request->input() + ['tableName' => $tableName];
			switch ($action) {
				case "changeOrder":
					$parentId = $params['parentId'];
					$parentType = $this->getParentTable($tableName);
					$params1 = [
						'parentType' => $parentType,
						'parentId' => $parentId,
						'guiType' => '',
					];
					//Only TOC can be changed order.
					if ($parentType == 'kanban_task_pages') $params1['guiType'] = 'cardPage';
					$wsResponse = new WssKanbanChannel($params + $params1);
					broadcast($wsResponse);
					break;
				case "updateItemRenderProps":
				case "changeName":

					break;
				case "addANewItem":
				case "deleteItemRenderProps":
					$wsResponse = new WssKanbanChannel($params);
					broadcast($wsResponse);
					break;
				case "changeParent":
				case "loadKanbanPage":
				case "editItemRenderProps":
					//Don't need to emit any response
					break;
			}
		} catch (\Exception $e) {
			return ResponseObject::responseFail($e->getMessage() . " Line " . $e->getLine());
		}
		return ResponseObject::responseFail("Unknown kanban action " . $action);
	}
}
