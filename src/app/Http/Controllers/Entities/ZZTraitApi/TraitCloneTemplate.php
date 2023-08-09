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
		$insertedId = null;
		$params = [];
		// $singular = Str::singular($this->type);
		$plural = Str::plural($this->type);
		switch ($plural) {
			case "ghg_sheets":
				$line = $lines[0];
				[
					'ghg_month' => $ghg_month,
					'ghg_tmpl_id' => $ghg_tmpl_id,
				] = $line;
				$params = [
					'--ownerId' => CurrentUser::id(),
					'--tmplId' => $ghg_tmpl_id,
					'--month' => $ghg_month,
				];
				$cmdOutput = Artisan::call("ndc:cloneGhg", $params);
				if ($cmdOutput) {
					$result = [['message' => "Problem ???"]];
				} else {
					$insertedId = trim(Artisan::output());
					$result = [['redirect_edit_href' => route($plural . '.edit', $insertedId),]];
				}
				break;
		}

		$response = ResponseObject::responseSuccess(
			$result,
			[$params],
			"Cloned from template #$ghg_tmpl_id",
		);
		return $response;
	}
}
