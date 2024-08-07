<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Pj_module;
use App\Models\Pj_unit;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait TraitEntityCRUDShowQRLandingPageModule
{
	public function showQRAppModule($slug, $trashed)
	{
		$modelCurrent = new ($this->modelPath);
		// $item = $trashed ? $modelCurrent::withTrashed()->where('slug', $slug)->first() : $modelCurrent::where('slug', $slug)->first();
		$item = $modelCurrent::where('slug', $slug)->first();
		if (!$item) {
			throw new NotFoundHttpException("Not found, #971.");
		}
		$props = SuperProps::getFor($this->type)['props'];

		$thumbnailUrl = $item->getSubProject?->getProject->getAvatarUrl("/images/generic-module1.webp");
		// dump($thumbnailUrl);
		$params = [
			'props' => $props,
			'item' => $item,
			'dataSource' => $this->getDataSourceGroups($item),
			'thumbnailUrl' => $thumbnailUrl,
			'type' => $this->type,
			'topTitle' => CurrentRoute::getTitleOf($this->type),
		];
		return view('dashboards.pages.entity-show-qr-landing-page', $params);
	}
	private function getDataSourceQARecords($model)
	{
		[$icsOfUnit, $icsOfModule] = $this->getDataRenderLinkDocsOfQARecord($model);
		$linkDocsOfUnit = [];
		$linkDocsOfModule = [];
		if ($icsOfUnit)
			foreach ($icsOfUnit as $value) {
				$href = route($value->getTable() . '.show', $value->id) ?? "";
				$name = $value->getQaqcInspTmpl->short_name ?? "";
				if ($href)
					$linkDocsOfUnit[] = [
						'href' => $href,
						'name' => $name,
					];
			}

		if ($icsOfModule)
			foreach ($icsOfModule as $value) {
				$href = route($value->getTable() . '.show', $value->id) ?? "";
				$name = $value->getQaqcInspTmpl->short_name ?? "";
				if ($href)
					$linkDocsOfModule[] = [
						'href' => $href,
						'name' => $name,
					];
			}

		if (!empty($linkDocsOfUnit))
			$result["Apartment:"] = $linkDocsOfUnit;
		if (!empty($linkDocsOfModule))
			$result["Module:"] = $linkDocsOfModule;
		return $result;
	}
	private function getDataSourceHOManual($model)
	{
		$attachments = $this->getDataRenderLinkDownloads($model);
		return $this->getLinkDownloadsByAttachments($attachments);
	}
	private function getDataSourceProjectPlans($model)
	{
		$attachments = $this->getDataRenderLinkDownloads($model, "attachment_subproject_project_plans");
		return $this->getLinkDownloadsByAttachments($attachments);
	}
	private function getLinkDownloadsByAttachments($attachments)
	{
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

				$qa_records = [
					"Apartment:" => [
						['name' => "MODULE Inspection Checklist", 'href' => $insp_chklst_link,]
					],
					"Module:" => [
						['name' => "SHIPPING / DELIVERY Checklist", 'href' => $shipping_doc_link,]
					],
				];
				break;
			default:
				break;
		}

		$cu = CurrentUser::get();
		// Log::info($cu);
		// dump($this->type);
		// Log::info($item);
		$unit_id = null;
		$project_id = null;
		switch ($this->type) {
			case "pj_unit":
				$unit_id = $item->id;
				$project_id = $item->project_id;
				break;
			case "pj_module":
				$unit_id = $item->pj_unit_id;
				$project_id = $item->getSubProject->project_id;
				break;
		}

		if ($cu) {
			$name = "Raise a TICKET";
			$href = route("crm_ticketings.create", [
				"house_owner_phone_number" => $cu->phone,
				"house_owner_id" => $cu->id,
				"house_owner_email" => $cu->email,
				"house_owner_address" => $cu->address,
				"unit_id" => $unit_id,
				"project_id" => $project_id,
			]);
		} else {
			$name = "Please Login to Raise a TICKET";
			$href = null;
		}

		$ticket = [[
			'name' => $name,
			'href' => $href,
		],];
		// dump($qa_records);
		return [
			'HomeOwner Manual' => [
				'items' => empty($ho_manuals) ? [
					[
						['name' => "To Be Added by DC"],
					]
				] : [
					$ho_manuals
				]
			],
			"Compliance Records" => [
				'tooltip' => $qa_source,
				'items' => empty($qa_records) ? [
					[
						['name' => "To Be Added",],
					]
				] : $qa_records
			],
			'Project Plans' => [
				'items' => empty($project_plans) ? [
					[
						['name' => "To Be Added by DC"],
					]
				] : [
					$project_plans
				]
			],
			'Ticketing' => [
				'items' => [
					$ticket
				],
			],
			'Customer Survey' => [
				'items' => [
					[
						['name' => "To Be Added by Software Team"],
					]
				]
			],
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
		$prodOrderOfUnit = Pj_unit::find($unitId)?->getProdOrders->first();
		$prodOrderOfModule = Pj_module::find($id)?->getProdOrders->first();

		$icsOfUnit = $prodOrderOfUnit?->getQaqcInspChklsts;
		$icsOfModule = $prodOrderOfModule?->getQaqcInspChklsts;

		return [$icsOfUnit, $icsOfModule];
	}
	private function getDataRenderLinkDownloads($item, $func = 'attachment_subproject_homeowner_manual')
	{
		$subProject = $item->getSubProject;
		$attachments = $subProject->{$func} ?? [];
		return $attachments;
	}
}
