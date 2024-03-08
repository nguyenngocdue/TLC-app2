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
	private function getDataSourceQARecords($model)
	{
		$dataRender = $this->getDataRenderLinkDocs($model);
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
	private function getDataSourceGroups($item)
	{
		$qr_app_source_id = $this->getConfigRenderSource($item);
		$qa_source = $qr_app_source_id == 529 ? '(ICS)' : (530 ? '(ConQA Archive)' : "Unknown");

		switch ($qr_app_source_id) {
			case 529:
				$qa_records = $this->getDataSourceQARecords($item);
				break;
			case 530:
				$qa_records = [];
				$insp_chklst_link = $item->insp_chklst_link;
				$shipping_doc_link = $item->shipping_doc_link;

				$qa_records[] = ['name' => "MODULE Inspection Checklist", 'href' => $insp_chklst_link,];
				$qa_records[] = ['name' => "SHIPPING Inspection Checklist", 'href' => $shipping_doc_link,];
				break;
			default:
				$qa_records = [];
				break;
		}

		return [
			'Home Owner Manual' => [['name' => "To Be Added"]],
			"QA Records $qa_source" =>  $qa_records,
			'Project Plans' => [['name' => "To Be Added"],],
			'Ticketing' => [['name' => "To Be Added"],],
			'Customer Survey' => [['name' => "To Be Added"],],
		];
	}
	public function getConfigRenderSource($item)
	{
		return $item->getSubProject?->getProject?->qr_app_source;
	}
	public function getDataRenderLinkDocs($item)
	{
		$unitId = $item->pj_unit_id;
		$id = $item->id;
		// $prodOrder = Prod_order::where('meta_type', $this->modelPath)->where('meta_id', $unitId)->first();
		$prodOrderOfUnit = Pj_unit::findOrFail($unitId)->getProdOrders->first() ?? [];
		$prodOrderOrModule = Pj_module::findOrFail($id)->getProdOrders->first() ?? [];

		$inspChecklists = $prodOrderOfUnit->getQaqcInspChklsts ?? [];
		$inspChecklists = $inspChecklists->merge($prodOrderOrModule->getQaqcInspChklsts ?? []);
		return $inspChecklists;
	}
}
