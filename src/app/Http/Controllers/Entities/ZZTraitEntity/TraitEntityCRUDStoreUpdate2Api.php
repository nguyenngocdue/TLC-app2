<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\DateTimeConcern;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;

trait TraitEntityCRUDStoreUpdate2Api
{
	// use TraitEntityFieldHandler2;
	// use TraitEntityAttachment2;
	// use TraitEntityEditableTable;
	// use TraitValidation;
	// use TraitSendNotificationAndMail;

	public function storeEmpty(Request $request)
	{
		$theRow = $this->modelPath::create($request->input());
		return ResponseObject::responseSuccess([['id' => $theRow->id]]);
	}

	public function updateShort(Request $request, $id)
	{
		$theRow = $this->modelPath::find($id);
		$input = $request->input();
		if (isset($input['ot_date'])) $input['ot_date'] = DateTimeConcern::convertForSaving('picker_date', $input['ot_date']);
		$theRow->fill($input);
		$result = $theRow->save();
		return ResponseObject::responseSuccess(
			[['result' => $result, 'input' => $input]],
			[],
			"UpdateShort"
		);
	}
}
