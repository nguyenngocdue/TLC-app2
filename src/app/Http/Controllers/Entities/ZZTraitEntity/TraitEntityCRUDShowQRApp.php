<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\SuperProps;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait TraitEntityCRUDShowQRApp
{
	public function showQRApp($slug, $trashed)
	{
		$modelCurrent = new ($this->data);
		$dataSource = $trashed ? $modelCurrent::withTrashed()->where('slug', $slug)->first() : $modelCurrent::where('slug', $slug)->first();
		if (!$dataSource) {
			throw new NotFoundHttpException();
		}
		$props = SuperProps::getFor($this->type)['props'];
		return view('dashboards.pages.entity-show-qr', [
			'props' => $props,
			'dataSource' => $dataSource,
			'type' => $this->type,
			'topTitle' => CurrentRoute::getTitleOf($this->type),
		]);
	}
}
