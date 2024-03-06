<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Prod_order;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\SuperProps;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait TraitEntityCRUDShowQRLandingPage
{
	public function showQRApp($slug, $trashed)
	{
		$modelCurrent = new ($this->modelPath);
		$item = $trashed ? $modelCurrent::withTrashed()->where('slug', $slug)->first() : $modelCurrent::where('slug', $slug)->first();
		if (!$item) {
			throw new NotFoundHttpException();
		}
		$props = SuperProps::getFor($this->type)['props'];
		$config  = $this->getConfigRenderSource($item);
		$dataRender = $this->getDataRenderLinkDocs($config, $item);
		$linkDocs = [];
		foreach ($dataRender as $value) {
			$href = route($value->getTable() . '.show', $value->id) ?? "";
			if ($href)
				$linkDocs[] = $href;
		}
		// dump($item);
		return view('dashboards.pages.entity-show-landing-page', [
			'props' => $props,
			'moduleName' => $item->name,
			'dataSource' => $linkDocs,
			'type' => $this->type,
			'topTitle' => CurrentRoute::getTitleOf($this->type),
		]);
	}
	public function getConfigRenderSource($item)
	{
		return $item->getSubProject?->getProject?->qr_app_source;
	}
	public function getDataRenderLinkDocs($config, $item)
	{
		switch ($config) {
			case 529: // QR_APP_SOURCE => mode render app
				$unitId = $item->pj_unit_id;
				$prodOrder = Prod_order::where('meta_type', 'App\\Models\\Pj_unit')->where('meta_id', $unitId)->first();
				$inspChecklists = $prodOrder->getQaqcInspChklsts->whereIn('qaqc_insp_tmpl_id', [1007, 3]) ?? [];
				return $inspChecklists;
			default:
				return [];
		}
	}
}
