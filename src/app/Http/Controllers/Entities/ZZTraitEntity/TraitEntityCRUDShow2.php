<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

trait TraitEntityCRUDShow2
{
	use TraitEntityCRUDShowProps;
	use TraitEntityCRUDShowProject;
	use TraitEntityCRUDShowReport;
	use TraitEntityCRUDShowQRLandingPageModule;
	use TraitEntityCRUDShowQRLandingPageUnit;

	use TraitEntityCRUDShowChklst2;
	use TraitEntityCRUDShowChklstSht2;

	public function show(Request $request, $id_or_slug, $trashed = false)
	{
		$app = LibApps::getFor($this->type);
		$show_renderer = $app['show_renderer'];
		switch ($show_renderer) {
			case '':
				return $this->showProps($id_or_slug, $trashed);
			case 'project-renderer':
				return $this->showProject($id_or_slug, $trashed);
			case 'checklist-renderer':
				return $this->showChklst2($id_or_slug, $trashed);
			case 'checklist-sheet-renderer':
				return $this->showChklstSht2($id_or_slug, $trashed);
			case 'qr-app-renderer':
				switch ($this->type) {
					case 'pj_module':
						return $this->showQRAppModule($id_or_slug, $trashed);
					case 'pj_unit':
						return $this->showQRAppUnit($id_or_slug, $trashed);
					default:
				}
			case 'report-renderer':
				return $this->showReport($request, $id_or_slug, $trashed);
			default:
				dump("Unknown how to render $show_renderer.");
		}
	}
	public function showTrashed(Request $request, $id_or_slug)
	{
		$this->show($request, $id_or_slug, true);
	}
}
