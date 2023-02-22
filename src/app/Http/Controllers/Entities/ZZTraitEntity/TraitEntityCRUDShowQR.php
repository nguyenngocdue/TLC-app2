<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\SuperProps;

trait TraitEntityCRUDShowQR
{
	public function showQR($slug)
	{
		$modelCurrent = new ($this->data);
		$dataSource = $modelCurrent::where('slug', $slug)->first();
		$props = SuperProps::getFor($this->type)['props'];
		return view('dashboards.pages.entity-show-qr', [
			'props' => $props,
			'dataSource' => $dataSource,
			'type' => $this->type,
			'topTitle' => CurrentRoute::getTitleOf($this->type),
		]);
	}
}
