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
		$docType = $request->input('docType');
		$ids = $request->input('ids');
		if ($ids) $ids = explode(",", $ids);

		$columns = [
			['dataIndex' => 'id', 'renderer' => 'id', 'type' => Str::plural($docType), 'align' => 'center', 'width' => 100],
			['dataIndex' => 'id', 'renderer' => 'qr-code', 'title' => 'QR Code', 'type' => Str::plural($docType), 'align' => 'center', 'width' => 60,],
			['dataIndex' => 'name', 'title' => 'Title', 'width' => 500],
			['dataIndex' => 'ball_in_court', 'width' => 200],
			['dataIndex' => 'status', 'renderer' => 'status', 'align' => 'center', 'width' => 150],
			['dataIndex' => 'due_date', 'renderer' => 'date_time', 'width' => 170],
		];

		$model = Str::modelPathFrom($docType);
		$dataSource = $model::whereIn('id', $ids)
			->orderBy('due_date', 'desc')
			->get();

		foreach ($dataSource as &$doc) {
			$uid = $doc->getCurrentBicId();
			if ($uid) $doc->ball_in_court = User::findFromCache($uid)->name;
		}

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
