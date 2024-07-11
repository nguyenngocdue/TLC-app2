<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Pj_module;
use App\Models\Pj_unit;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait TraitEntityCRUDShowQRLandingPageUnit
{
	public function showQRAppUnit($slug, $trashed)
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
			'dataSource' => [
				'To be developed functions' => [
					[['name' => 'To be developed functions']]
				],
			],
			'thumbnailUrl' => $thumbnailUrl,
			'type' => $this->type,
			'topTitle' => CurrentRoute::getTitleOf($this->type),
		];
		return view('dashboards.pages.entity-show-qr-landing-page', $params);
	}
}
