<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Prod_order;
use App\Models\Qaqc_insp_chklst_sht;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\SuperProps;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait TraitEntityCRUDShowQRApp
{
	public function showQRApp($slug, $trashed)
	{
		$modelCurrent = new ($this->modelPath);
		$model = $trashed ? $modelCurrent::withTrashed()->where('slug', $slug)->first() : $modelCurrent::where('slug', $slug)->first();
		if (!$model) {
			throw new NotFoundHttpException();
		}
		$props = SuperProps::getFor($this->type)['props'];
		$config  = $this->getConfigRenderSource($model);
		$dataRender = $this->getDataRenderLinkDocs($config,$model);
		$linkDocs = [];
		foreach ($dataRender as $value) {
			$href = route($value->getTable().'.show',$value->id) ?? "";
			if($href)
				$linkDocs[] = $href;
		}
		return view('dashboards.pages.entity-show-qr', [
			'props' => $props,
			'moduleName' => $model->name,
			'dataSource' => $linkDocs,
			'type' => $this->type,
			'topTitle' => CurrentRoute::getTitleOf($this->type),
		]);
	}
	public function getConfigRenderSource($model){
		return $model->getSubProject?->getProject?->qr_app_source;
	}
	public function getDataRenderLinkDocs($config, $model){
		switch ($config) {
			case 529: // QR_APP_SOURCE => mode render app
				$unitId = $model->pj_unit_id;
				$prodOrder = Prod_order::where('meta_type','App\\Models\\Pj_unit')->where('meta_id',$unitId)->first();
				$inspChecklists = $prodOrder->getQaqcInspChklsts->whereIn('qaqc_insp_tmpl_id',[1007,3]) ?? [];
				return $inspChecklists;				
			default:
				return [];
		}
	}
}
