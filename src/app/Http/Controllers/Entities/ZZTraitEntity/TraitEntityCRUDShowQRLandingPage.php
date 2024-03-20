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
			'item' => $item,
			'dataSource' => $this->getDataSourceGroups($item),
			'thumbnailUrl' => $thumbnailUrl,
			'type' => $this->type,
			'topTitle' => CurrentRoute::getTitleOf($this->type),
		]);
	}
	private function getDataSourceQARecords($model)
	{
		$dataRender = $this->getDataRenderLinkDocsOfQARecord($model);
		$linkDocs = [];
		foreach ($dataRender as $value) {
			$href = route($value->getTable() . '.show', $value->id) ?? "";
			$name = $value->getQaqcInspTmpl->short_name ?? "";
			if ($href)
				$linkDocs[] = [
					'href' => $href,
					'name' => $name,
				];
		}
		return $linkDocs;
	}
	private function getDataSourceHOManual($model){
		$attachments = $this->getDataRenderLinkDownloads($model);
		return $this->getLinkDownloadsByAttachments($attachments);
	}
	private function getDataSourceProjectPlans($model){
		$attachments = $this->getDataRenderLinkDownloads($model,"attachment_subproject_project_plans");
        return $this->getLinkDownloadsByAttachments($attachments);
	}
	private function getLinkDownloadsByAttachments($attachments){
		$linkDownloads = [];
		$pathMinio = app()->pathMinio();
		foreach ($attachments as $attachment) {
			$linkDownloads[] = [
				'href' => $pathMinio . $attachment->url_media,
				'name' => $attachment->filename,
			];
		}
		return $linkDownloads;
	}
	private function getDataSourceGroups($item)
	{
		$qr_app_source_id = $this->getConfigRenderSource($item);
		$qa_source = $qr_app_source_id == 529 ? '(ICS)' : (530 ? '(ConQA Archive)' : "Unknown");
		$qa_records = [];
		$ho_manuals = [];
		$project_plans = [];
		switch ($qr_app_source_id) {
			case 529: //App
				$qa_records = $this->getDataSourceQARecords($item);
				$ho_manuals = $this->getDataSourceHOManual($item);
				$project_plans = $this->getDataSourceProjectPlans($item);
				break;
			case 530: // ConQA Archive
				$insp_chklst_link = $item->insp_chklst_link;
				$shipping_doc_link = $item->shipping_doc_link;

				$qa_records[] = ['name' => "MODULE Inspection Checklist", 'href' => $insp_chklst_link,];
				$qa_records[] = ['name' => "SHIPPING / DELIVERY Checklist", 'href' => $shipping_doc_link,];
				break;
			default:
				break;
		}

		return [
			'HomeOwner Manual' => empty($ho_manuals) ? [['name' => "To Be Added"],] : $ho_manuals,
			"QA Records $qa_source" =>  empty($qa_records) ? [['name' => "To Be Added"],] : $qa_records,
			'Project Plans' => empty($project_plans) ? [['name' => "To Be Added"],] : $project_plans,
			'Ticketing' => [['name' => "To Be Added"],],
			'Customer Survey' => [['name' => "To Be Added"],],
		];
	}
	public function getConfigRenderSource($item)
	{
		return $item->getSubProject?->getProject?->qr_app_source;
	}
	private function getDataRenderLinkDocsOfQARecord($item)
	{
		$unitId = $item->pj_unit_id;
		$id = $item->id;
		// $prodOrder = Prod_order::where('meta_type', $this->modelPath)->where('meta_id', $unitId)->first();
		$prodOrderOfUnit = Pj_unit::find($unitId)?->getProdOrders->first() ?? [];
		$prodOrderOrModule = Pj_module::find($id)?->getProdOrders->first() ?? [];

		$inspChecklists = $prodOrderOfUnit->getQaqcInspChklsts ?? null;
		if ($inspChecklists) {
			return $inspChecklists->merge($prodOrderOrModule->getQaqcInspChklsts ?? []);
		} else {
			return $prodOrderOrModule->getQaqcInspChklsts;
		}
	}
	private function getDataRenderLinkDownloads($item, $func = 'attachment_subproject_homeowner_manual'){
		$subProject = $item->getSubProject;
		$attachments = $subProject->{$func} ?? [];
		return $attachments;
	}
}
