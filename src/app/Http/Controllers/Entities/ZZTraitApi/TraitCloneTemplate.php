<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitCloneTemplate
{
	public function cloneTemplate(Request $request)
	{
		$lines = $request->input('lines');
		// dump($lines);
		$line = $lines[0] ?? [];
		// dump($line);
		$insertedId = null;
		$params = $line;
		$plural = Str::plural($this->type);
		// dump($plural);
		$tmpl_id = "?????";
		$ownerId = CurrentUser::id();
		switch ($plural) {
			case "ghg_sheets":
				['ghg_month' => $ghg_month, 'ghg_tmpl_id' => $tmpl_id,] = $line;
				$params = ['--ownerId' => $ownerId, '--tmplId' => $tmpl_id, '--month' => $ghg_month,];
				$cmdOutput = Artisan::call("ndc:cloneGhg", $params);
				if ($cmdOutput) {
					$result = [['message' => "Problem ???"]];
				} else {
					$insertedId = trim(Artisan::output());
					$result = [['redirect_edit_href' => route($plural . '.edit', $insertedId),]];
				}
				break;
			case "hse_insp_chklst_shts":
				['tmpl_id' => $tmpl_id,] = $line;
				$params = ['--ownerId' => $ownerId, '--inspTmplId' => $tmpl_id,];
				$cmdOutput = Artisan::call("ndc:cloneHse", $params);
				if ($cmdOutput) {
					$result = [['message' => "Problem ???"]];
				} else {
					$insertedId = trim(Artisan::output());
					$result = [['redirect_edit_href' => route($plural . '.edit', $insertedId),]];
				}
				break;
			case "qaqc_insp_chklst_shts":
				['qaqc_insp_tmpl_sht_id' => $tmpl_id, "qaqc_insp_chklst_id" => $chklst_id] = $line;
				$params = ['--ownerId' => $ownerId, '--inspChklstId' => $chklst_id, '--inspTmplShtId' => $tmpl_id];
				$cmdOutput = Artisan::call("ndc:cloneQaqcSheet", $params);
				if ($cmdOutput) {
					$result = [['message' => "Problem ???"]];
				} else {
					$insertedId = trim(Artisan::output());
					$result = [['redirect_edit_href' => route($plural . '.edit', $insertedId),]];
				}
				break;
			default:
				$result = [];
				dump("[$plural] is not in the switch");
				break;
		}

		$response = ResponseObject::responseSuccess(
			$result,
			[$params],
			"Cloned $plural from template #$tmpl_id",
		);
		return $response;
	}
}
