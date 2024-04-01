<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

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
		switch ($routeView) {
			case 'back':
				return redirect()->back();
			case 'index':
				$type = $this->type;
				switch ($this->type) {
					case 'esg_sheet':
						$type = 'esg_master_sheet';
						break;
					case 'qaqc_punchlist':
						$type = 'qaqc_insp_chklst_sht';
						break;
				}
				$urlRedirect = route(Str::plural($type) . ".index");
				break;
			case 'edit':
				$urlRedirect = route(Str::plural($this->type) . ".edit", $theRow->id);
				break;
			default:
				# code...
				break;
		}
		return redirect($urlRedirect);
	}
}
