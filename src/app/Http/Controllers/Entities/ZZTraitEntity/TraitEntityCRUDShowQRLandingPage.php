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
		return view('dashboards.pages.entity-show-landing-page', [
			'props' => $props,
			'moduleName' => $item->name,
			'dataSource' => $this->getDataSourceGroups($item),
			'type' => $this->type,
			'topTitle' => CurrentRoute::getTitleOf($this->type),
		]);
	}
	private function getDataSourceQARecords($model){
		$config  = $this->getConfigRenderSource($model);
		$dataRender = $this->getDataRenderLinkDocs($config, $model);
		$linkDocs = [];
		foreach ($dataRender as $value) {
			$href = route($value->getTable() . '.show', $value->id) ?? "";
			$name = $value->getQaqcInspTmpl->name ?? "";
			if ($href)
				$linkDocs[] = [
					'href' => $href,
                    'name' => $name,
				];
		}
		return $linkDocs;
	}
	private function getDataSourceGroups($model){
		return [
			'Home Owner Manual' => [],
			'QA Records' => $this->getDataSourceQARecords($model),
			'Project Plans' => [],
			'Ticketing' => [],
			'Customer Survey' => [],
		];
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
				$prodOrder = Prod_order::where('meta_type', $this->modelPath)->where('meta_id', $unitId)->first();
				$inspChecklists = $prodOrder->getQaqcInspChklsts ?? [];
				return $inspChecklists;
			default:
				return [];
		}
	}
}
