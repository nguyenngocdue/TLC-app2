<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityDynamicType;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFormula;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitSupportEntityCRUDCreateEdit2;
use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\Json\SuperProps;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EntityCRUDControllerForApi extends Controller
{
	use TraitEntityDynamicType;
	use TraitEntityFormula;
	use TraitSupportEntityCRUDCreateEdit2;

	protected $type;
	protected $modelPath;
	protected $superProps;

	protected $uploadService2;

	//construction has to be no argument as for further instantiation of EditableTable
	public function __construct()
	{
		$this->assignDynamicTypeCreateEditForApi();

		// $this->uploadService2 = App::make('App\Http\Services\UploadService2');

		$this->superProps = SuperProps::getFor($this->type);
	}

	// public function getType()
	// {
	// 	return $this->type;
	// }

	public function storeEmpty(Request $request)
	{
		$sp = SuperProps::getFor($this->type);
		$props = $sp['props'];
		$lines = $request->get('lines');
		$theRows = [];
		$defaultValue = $this->getDefaultValue($props);
		foreach ($lines as $item) {
			foreach ($defaultValue as $key => $value) {
				if (!isset($item[$key])) $item[$key]  = $value;
			}
			if (isset($item['ot_date'])) {
				$item['ot_date'] = DateTimeConcern::convertForSaving('picker_date', $item['ot_date']);
			}
			$item = $this->applyFormula($item, 'store');
			$theRows[] = $this->modelPath::create($item);
		}
		return ResponseObject::responseSuccess(
			$theRows,
			['defaultValue' => $defaultValue],
			"Created " . sizeof($theRows) . " lines",
		);
	}

	public function updateShort(Request $request)
	{
		$lines = $request->input('lines');
		// dump($lines);
		$result = [];
		foreach ($lines as $input) {
			$id = $input['id'];
			$fieldName = $input['fieldName'];
			$value = $input['value'];

			$theRow = $this->modelPath::find($id);
			if ($fieldName == 'ot_date') $value = DateTimeConcern::convertForSaving('picker_date', $value);
			$theRow->fill([$fieldName => $value]);
			$result[$id] = $theRow->save();
		}
		return ResponseObject::responseSuccess(
			$result,
			$lines,
			"Updated " . sizeof($result) . " lines",
		);
	}
}
