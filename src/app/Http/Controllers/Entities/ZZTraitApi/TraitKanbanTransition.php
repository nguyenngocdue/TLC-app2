<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Models\Kanban_task_transition;
use App\Utils\Support\CurrentUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait TraitKanbanTransition
{
	use TraitKanbanWorkingShift;

	function leavingGroup($item)
	{
		$kanban_task_transition_id = $item->kanban_task_transition_id;
		if (is_null($kanban_task_transition_id)) return;
		$transition = Kanban_task_transition::find($kanban_task_transition_id);
		if (is_null($transition)) return;
		$transition->end_at = now();
		// $start_at = Carbon::parse($transition->start_at);
		// $transition->elapsed_seconds = Carbon::now()->diffInSeconds($start_at);

		$uid = $item->assignee_1;
		['shift_seconds' => $elapse, 'non_shift_seconds' => $excluded] = $this->calculateShiftDurationByUser($transition->start_at, now(), $uid);
		$transition->elapsed_seconds = $elapse;
		$transition->excluded_seconds = $excluded;

		$transition->save();
	}

	function enteringGroup($item, $parent_id)
	{
		$cuid = CurrentUser::id();
		$kanban_task_transition = Kanban_task_transition::create([
			"kanban_task_id" => $item->id,
			"kanban_group_id" => $parent_id,
			"start_at" => now(),
			"owner_id" => $cuid,
		]);

		return $kanban_task_transition->id;
	}

	function setTransitionLog($item, $newParentId)
	{
		$this->leavingGroup($item);
		return $this->enteringGroup($item, $newParentId);
	}
}
