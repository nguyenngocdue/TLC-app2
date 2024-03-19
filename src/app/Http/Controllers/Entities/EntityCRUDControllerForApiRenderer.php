<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityDynamicType;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFormula;
use App\Models\User;
use App\Utils\Support\Json\SuperProps;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EntityCRUDControllerForApiRenderer extends Controller
{
	use TraitEntityDynamicType;
	use TraitEntityFormula;

	protected $type;
	protected $modelPath;
	protected $superProps;

	protected $uploadService2;

	//construction has to be no argument as for further instantiation of EditableTable
	public function __construct()
	{
		$this->assignDynamicTypeCreateEditForApi();
		$this->superProps = SuperProps::getFor($this->type);
	}

	public function renderTable(Request $request)
	{
		$tableName = $request->input('tableName');
		$ids = $request->input('ids');
		if ($ids) $ids = explode(",", $ids);

		$columns = [
			['dataIndex' => 'id', 'renderer' => 'id', 'type' => $tableName, /*'align' => 'center',*/ 'width' => 100],
			['dataIndex' => 'id', 'renderer' => 'qr-code', 'title' => 'QR Code', 'type' => $tableName, 'align' => 'center', 'width' => 60,],
			['dataIndex' => 'due_date', 'renderer' => 'date_time', 'width' => 170],
			['dataIndex' => 'project_name', 'width' => 100, 'align' => 'center'],
			['dataIndex' => 'sub_project_name', 'width' => 100, 'align' => 'center'],
			['dataIndex' => 'name', 'title' => 'Title', 'width' => 500, 'align' => 'left'],
			['dataIndex' => 'ball_in_court', 'width' => 200, 'align' => 'left'],
			['dataIndex' => 'status', 'renderer' => 'status', 'align' => 'center', 'width' => 150],
		];

		$model = Str::modelPathFrom($tableName);

		$hasProject = method_exists($model, 'getProject');
		$hasSubProject = method_exists($model, 'getSubProject');

		$dataSource = $model::whereIn('id', $ids);
		if ($hasProject) $dataSource = $dataSource->with("getProject");
		if ($hasSubProject) $dataSource = $dataSource->with("getSubProject");

		$dataSource = $dataSource->orderBy('due_date')
			->get();

		foreach ($dataSource as &$doc) {
			$uid = $doc->getCurrentBicId();
			if ($uid) $doc->ball_in_court = User::findFromCache($uid)->name;
			$doc->project_name = $doc->getProject?->name;
			$doc->sub_project_name = $doc->getSubProject?->name;
		}

		// dump($dataSource);
		// Log::info($doc);

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
