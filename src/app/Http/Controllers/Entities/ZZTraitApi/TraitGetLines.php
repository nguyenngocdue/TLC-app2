<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\BigThink\Oracy;
use App\Models\Hr_overtime_request;
use App\Models\Hr_overtime_request_line;
use App\Models\Hr_timesheet_line;
use App\Models\Hr_timesheet_worker;
use App\Models\Prod_routing_link;
use App\Models\Prod_run;
use App\Models\Prod_sequence;
use App\Models\Sub_project;
use App\Models\User;
use App\Models\User_team_tsht;
use App\Utils\Support\CurrentUser;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitGetLines
{
	private function getTeamMembers($team_id)
	{
		return User_team_tsht::find($team_id)->getTshtMembers()->pluck('id');
	}

	private function getCacheData($allSequences)
	{
		$allSubProjects = [];
		$allRoutingLinks = [];
		$allSequences0 = Prod_sequence::whereIn('id', array_keys($allSequences))->get();
		$allSequences = [];
		foreach ($allSequences0 as $sequence) {
			$allSequences[$sequence->id] = $sequence;
			$allSubProjects[$sequence->sub_project_id] = true;
			$allRoutingLinks[$sequence->prod_routing_link_id] = true;
		}

		$allSubProjects0 = Sub_project::whereIn('id', array_keys($allSubProjects))->get();
		$allSubProjects = [];
		foreach ($allSubProjects0 as $subProject) $allSubProjects[$subProject->id] = $subProject;

		$allRoutingLinks0 = Prod_routing_link::whereIn('id', array_keys($allRoutingLinks))->get();
		$allRoutingLinks = [];
		foreach ($allRoutingLinks0 as $routingLink) $allRoutingLinks[$routingLink->id] = $routingLink;

		return [$allSequences, $allSubProjects, $allRoutingLinks];
	}

	private function makeData($team_id, $date)
	{
		$runs = Prod_run::query()->where('date', $date)->get();
		Oracy::attach("getWorkersOfRun()", $runs);

		$result = [];
		$allSequences = [];
		$teamMembers = $this->getTeamMembers($team_id)->toArray();
		foreach ($runs as $run) {
			foreach ($run->{"getWorkersOfRun()"} as $worker_id) {
				if (in_array($worker_id, $teamMembers)) {
					$allSequences[$run->prod_sequence_id] = true;
					// if (isset($result[$worker_id]['all']['total_hours'])) {
					// 	$result[$worker_id]['all']['total_hours'] += $run->total_hours;
					// } else {
					// 	$result[$worker_id]['all']['total_hours'] = $run->total_hours;
					// }
					// if ($result[$worker_id]['all']['total_hours'] > 8) {
					// 	$ot_hour = $result[$worker_id]['all']['total_hours'] - 8;
					// 	$result[$worker_id]['all']['total_hours'] = 8;
					// 	if (isset($result[$worker_id]['all']['ot_hours'])) {
					// 		$result[$worker_id]['all']['ot_hours'] = $ot_hour;
					// 	} else {
					// 		$result[$worker_id]['all']['ot_hours'] += $ot_hour;
					// 	}
					// }

					if (isset($result[$worker_id][$run->prod_sequence_id])) {
						$result[$worker_id][$run->prod_sequence_id]['total_hours'] += $run->total_hours;
						$result[$worker_id][$run->prod_sequence_id]['date'] = $run->date;
					} else {
						$result[$worker_id][$run->prod_sequence_id]['total_hours'] = $run->total_hours;
						$result[$worker_id][$run->prod_sequence_id]['date'] = $run->date;
					}

					// if ($result[$worker_id][$run->prod_sequence_id]['total_hours'] > 8) {
					// 	$ot_hour = $result[$worker_id][$run->prod_sequence_id]['total_hours'] - 8;
					// 	$result[$worker_id][$run->prod_sequence_id]['total_hours'] = 8;
					// 	$result[$worker_id][$run->prod_sequence_id]['ot_hours'] += $ot_hour;
					// }
				}
			}
		}

		return [$result, $allSequences];
	}

	private function emptyCurrentTSW($parent_id)
	{
		$lines = Hr_timesheet_worker::find($parent_id)->getHrTsLines()->get();
		$ids = $lines->pluck('id');
		// Log::info($ids);
		Hr_timesheet_line::destroy($ids);
	}

	private function setCurrentTSWLines($result, $allSequences, $allSubProjects, $allRoutingLinks, $parent_id)
	{
		foreach ($result as $worker_id => $manySheets) {
			$user = User::findFromCache($worker_id);
			foreach ($manySheets as $sequence_id => $line) {
				$sequence = $allSequences[$sequence_id];
				$item = [
					'timesheetable_type' => 'App\Models\Hr_timesheet_worker',
					'timesheetable_id' => $parent_id,
					'user_id' => $worker_id,
					'discipline_id' => $user->discipline,

					'ts_date' => $line['date'],
					'duration_in_min' => $line['total_hours'] * 60,
					'duration_in_hour' => $line['total_hours'],

					'project_id' => $allSubProjects[$sequence['sub_project_id']]->project_id,
					'sub_project_id' => $sequence['sub_project_id'],
					'prod_routing_id' => $sequence['prod_routing_id'],
					'remark' => $allRoutingLinks[$sequence['prod_routing_link_id']]->description,

					'lod_id' => 228, // 228: LOD400
					'work_mode_id' => 1, //1: NZ, 2: TF, 3: WFH

					'owner_id' => CurrentUser::id(),
				];
				Hr_timesheet_line::create($item);
			}
		}
	}

	private function makeNewOTR()
	{
		$otr = Hr_overtime_request::create([
			'workplace_id' => 5, //5:NZO
			// 'user_team_ot_id' => 3, //3: NZ - Worker 2 (HLC)
			'description' => "Work at NZ site",
			'assignee_1' => 35,
			'status' => 'new',

			'owner_id' => CurrentUser::id(),
		]);
		return $otr;
	}

	private function setOTRLines($otr, $date, $result, $allSequences)
	{
		$id = $otr->id;
		// Log::info($id);
		Log::info($result);

		foreach ($result as $worker_id => $manySheets) {
			$user = User::findFromCache($worker_id);
			foreach ($manySheets as $sequence_id => $line) {
				$sequence = $allSequences[$sequence_id];
				$item = [
					'hr_overtime_request_id' => $id,
					'user_id' => $worker_id,
					'employeeid' => $user->employeeid,
					'position_rendered' => $user->position_rendered,
					'ot_date' => $date,
					'from_time' => '16:00:00',
					'to_time' => '18:00:00',
					'break_time' => 0,
					'total_time' => 123456,
					'sub_project_id' => $sequence['sub_project_id'],
					'work_mode_id'  => 1, //1: NZSite
					'remark' => 'NZ Site Work',

					'owner_id' => $otr->owner_id,
					'status' => 'new',
				];
				Hr_overtime_request_line::create($item);
			}
		}
	}

	public function getLines(Request $request)
	{
		$line = $request->input('lines')[0];
		['date' => $date, 'team_id' => $team_id, 'parent_id' => $parent_id] = $line;

		[$result, $allSequences] = $this->makeData($team_id, $date);
		Log::info($result);
		[$allSequences, $allSubProjects, $allRoutingLinks] = $this->getCacheData($allSequences);
		$this->emptyCurrentTSW($parent_id);
		$this->setCurrentTSWLines($result, $allSequences, $allSubProjects, $allRoutingLinks, $parent_id);
		// $otr = $this->makeNewOTR();
		// $otr = (object)['id' => 123456];
		// $this->setOTRLines($otr, $date, $result, $allSequences);

		return ResponseObject::responseSuccess($result, ['line' => $line], "AABBCC");
	}
}
