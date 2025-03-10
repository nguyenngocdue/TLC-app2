<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait TraitEntityShowQRList6
{
	public function showQRList6()
	{
		[, $dataSource] = $this->normalizeDataSourceAndColumnsByAdvanceFilter();
		$plural = Str::plural($this->type);
		$routeName = "{$plural}.show";
		$routeExits =  (Route::has($routeName));
		// dump($dataSource);
		if ($this->type == 'pj_module') {
			return view('dashboards.pages.entity-show-qr-list6-module', [
				'dataSource' => $dataSource,
				'type' => $this->type,
				'topTitle' => CurrentRoute::getTitleOf($this->type),
				'routeName' => $routeName,
			]);
		} else {
			$dataSource = array_map(function ($item) use ($routeExits, $routeName) {
				return [
					'href' => $routeExits ? route($routeName, $item['slug']) : "#",
					'name' => $item['name'] ?? $item['filename'] ?? '',
				];
			}, $dataSource->toArray());
			$dataSource = array_chunk($dataSource, 6);
			return view('dashboards.pages.entity-show-qr-list6', [
				'dataSource' => $dataSource,
				'type' => $this->type,
				'topTitle' => CurrentRoute::getTitleOf($this->type),
			]);
		}
	}
}
