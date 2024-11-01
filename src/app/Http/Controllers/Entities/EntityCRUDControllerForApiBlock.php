<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

class EntityCRUDControllerForApiBlock extends Controller
{
	function renderBlock(Request $request)
	{
		// Log::info("renderBlock");
		Log::info($request->all());

		$columns = [
			["dataIndex" => "name", "title" => "Name"],
			["dataIndex" => "title", "title" => "Title"],
		];

		$dataSource = [
			["name" => "John", "title" => "123456"],
			["name" => "Doe", "title" => "456798"],
		];

		$blade = '<x-renderer.table :columns="$columns" :dataSource="$dataSource" maxH=35 tableTrueWidth=0 ></x-renderer.table>';
		$result = Blade::render($blade, [
			'columns' => $columns,
			'dataSource' => $dataSource,
		]);

		return ResponseObject::responseSuccess(
			$result,
		);
	}
}
