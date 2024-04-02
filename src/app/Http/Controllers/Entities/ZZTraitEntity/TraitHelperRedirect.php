<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Services\MatrixFilterParamService;
use App\Utils\Support\JsonControls;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitHelperRedirect
{
	private function redirectCustomForUpdate2($request, $theRow)
	{
		$routeView = 'edit';
		if ($request->input('redirect_back_to_last_page')) $routeView = 'back';
		if ($request->input('saveAndClose') == 'true') $routeView = 'index';
		$urlRedirect = '';
		$pluralType = Str::plural($this->type);
		switch ($routeView) {
			case 'back':
				return redirect()->back();
			case 'index':
				$matrixApps = JsonControls::getAppsHaveViewAllMatrix();
				$params = [];
				if (in_array($pluralType, $matrixApps)) {
					$params = MatrixFilterParamService::get($pluralType);
				}
				$paramStr = sizeof($params) > 0 ? '?' . http_build_query($params, '', '&') : '';

				switch ($pluralType) {
					case 'esg_sheets':
						$pluralType = 'esg_master_sheets';
						break;
					case 'qaqc_punchlists':
						$pluralType = 'qaqc_insp_chklst_shts';
						break;
				}
				$urlRedirect = route($pluralType . ".index") . $paramStr;
				break;
			case 'edit':
				$urlRedirect = route($pluralType . ".edit", $theRow->id);
				break;
			default:
				# code...
				break;
		}
		return redirect($urlRedirect);
	}
}
