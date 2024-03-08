<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Pj_module;
use App\Models\Pj_unit;
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

		$thumbnailUrl = $item->getSubProject?->getProject->getAvatarUrl() ?? "/images/generic-module1.webp";
		return view('dashboards.pages.entity-show-landing-page', [
			'props' => $props,
			'moduleName' => $item->name,
			'dataSource' => $this->getDataSourceGroups($item),
			'thumbnailUrl' => $thumbnailUrl,
			'type' => $this->type,
			'topTitle' => CurrentRoute::getTitleOf($this->type),
		]);
	}
	private function getDataSourceQARecords($qr_app_source_id, $model)
	{
		$dataRender = $this->getDataRenderLinkDocs($qr_app_source_id, $model);
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
	private function getDataSourceGroups($model)
	{
		$qr_app_source_id = $this->getConfigRenderSource($model);
		$qa_source = $qr_app_source_id == 529 ? '(ICS)' : '(ConQA Archive)';

		return [
			'Home Owner Manual' => [],
			"QA Records $qa_source" => $this->getDataSourceQARecords($qr_app_source_id, $model),
			'Project Plans' => [],
			'Ticketing' => [],
			'Customer Survey' => [],
		];
	}
	public function getConfigRenderSource($item)
	{
		return $item->getSubProject?->getProject?->qr_app_source;
	}
	public function getDataRenderLinkDocs($qr_app_source, $item)
	{
		switch ($qr_app_source) {
			case 529: // Internal ICS
				$unitId = $item->pj_unit_id;
				$id = $item->id;
				// $prodOrder = Prod_order::where('meta_type', $this->modelPath)->where('meta_id', $unitId)->first();
				$prodOrderOfUnit = Pj_unit::findOrFail($unitId)->getProdOrders->first() ?? [];
				$prodOrderOrModule = Pj_module::findOrFail($id)->getProdOrders->first() ?? [];

				$inspChecklists = $prodOrderOfUnit->getQaqcInspChklsts ?? [];
				$inspChecklists = $inspChecklists->merge($prodOrderOrModule->getQaqcInspChklsts ?? []);
				return $inspChecklists;
			case 530: // Conqa Archive
				return [];
			default:
				return [];
		}
	}
}
