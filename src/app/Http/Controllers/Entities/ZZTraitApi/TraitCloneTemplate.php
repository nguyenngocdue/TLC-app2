<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Utils\Support\CurrentUser;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitCloneTemplate
{
	use TraitFailObject;

	private function getResult($command, $params, $plural, $tmpl_id)
	{
		$cmdOutput = Artisan::call($command, $params);
		$artisanOutput = Artisan::output();
		if ($cmdOutput) {
			throw new \Exception("Problem: " . $artisanOutput);
		} else {
			$insertedId = trim($artisanOutput);
			$result = [
				"message" => "Cloned $plural from template #$tmpl_id",
				"result" => ['redirect_edit_href' => route($plural . '.edit', $insertedId),]
			];
		}
		return $result;
	}

	public function cloneTemplate(Request $request)
	{
		$lines = $request->input('lines');
		// dump($lines);
		$line = $lines[0] ?? [];
		// dump($line);
		$params = $line;
		$plural = Str::plural($this->type);
		// dump($plural);
		$tmpl_id = "?????";
		$ownerId = CurrentUser::id();
		try {
			switch ($plural) {
				case "ghg_sheets":
					['ghg_month' => $ghg_month, 'ghg_tmpl_id' => $tmpl_id,] = $line;
					$params = ['--ownerId' => $ownerId, '--tmplId' => $tmpl_id, '--month' => $ghg_month,];
					$command = "ndc:cloneGhg";
					$result = $this->getResult($command, $params, $plural, $tmpl_id);
					break;
				case "hse_insp_chklst_shts":
					['tmpl_id' => $tmpl_id,] = $line;
					$params = ['--ownerId' => $ownerId, '--inspTmplId' => $tmpl_id,];
					$command = "ndc:cloneHse";
					$result = $this->getResult($command, $params, $plural, $tmpl_id);
					break;
				case "qaqc_insp_chklst_shts":
					['qaqc_insp_tmpl_sht_id' => $tmpl_id, "qaqc_insp_chklst_id" => $chklst_id] = $line;
					$params = ['--ownerId' => $ownerId, '--inspChklstId' => $chklst_id, '--inspTmplShtId' => $tmpl_id];
					$command = "ndc:cloneQaqcSheet";
					$result = $this->getResult($command, $params, $plural, $tmpl_id);
					break;
				default:
					$result = [
						'message' => "[$plural] is not in the switch",
						'result' => [[]],
					];
					break;
			}

			$hits = $result['result'];
			$message = $result['message'];
			$response = ResponseObject::responseSuccess(
				$hits,
				[$params],
				$message,
			);
			return $response;
		} catch (\Throwable $th) {
			return $this->getFailObject($th);
		}
	}
}
