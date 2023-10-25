<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use Illuminate\Support\Facades\Blade;

trait TraitKanbanItemRenderer
{
	function renderKanbanItem($table, $insertedObj, $groupWidth = 'w-72')
	{
		$renderer = "";
		switch ($table) {
			case 'kanban_tasks':
				$group = $insertedObj->getParent;
				$renderer = Blade::render('<x-renderer.kanban.task :task="$task" :group="$group" groupWidth="{{$groupWidth}}"/>', [
					'task' => $insertedObj,
					'groupWidth' => $groupWidth,
					'group' => $group,
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
			case 'kanban_task_pages':
				$renderer = Blade::render('<x-renderer.kanban.page :page="$page" groupWidth="{{$groupWidth}}"/>', [
					'page' => $insertedObj,
					'groupWidth' => $groupWidth,
				]);
				break;
			case 'kanban_task_tocs': //<< This is a fake table
				$renderer = Blade::render('<x-renderer.kanban.toc :page="$page" groupWidth="{{$groupWidth}}"/>', [
					'page' => $insertedObj,
					'groupWidth' => $groupWidth,
				]);
				break;
			case "kanban_task_buckets":
				$renderer = Blade::render('<x-renderer.kanban.bucket :bucket="$bucket" groupWidth="{{$groupWidth}}"/>', [
					'bucket' => $insertedObj,
					'groupWidth' => $groupWidth,
				]);
				break;
				// case "all_buckets":
				// 	$renderer = Blade::render('<x-renderer.kanban.buckets :page="$page" groupWidth="{{$groupWidth}}"/>', [
				// 		'page' => $insertedObj,
				// 		'groupWidth' => $groupWidth,
				// 	]);
				// 	break;
			default:
				$renderer = "Unknown how to render kanban for $table";
				break;
		}

		return $renderer;
	}
}
