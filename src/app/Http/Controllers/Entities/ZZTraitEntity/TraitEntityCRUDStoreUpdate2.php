<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\DateTimeConcern;
use App\Utils\System\Api\ResponseObject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait TraitEntityCRUDStoreUpdate2
{
	use TraitEntityFieldHandler2;
	use TraitEntityAttachment2;
	use TraitEntityEditableTable;
	use TraitEntityFormula;
	use TraitValidation;
	use TraitSendNotificationAndMail;

	private $debugForStoreUpdate = false;

	private function dump1($title, $content, $line)
	{
		if ($this->debugForStoreUpdate) {
			echo "$title in file " . basename(__FILE__) . " line $line";
			dump($content);
		}
	}

	public function store(Request $request)
	{
		try {
			$this->dump1("Request", $request->input(), __LINE__);
			$props = $this->getProps1();
			$this->deleteAttachments($props['attachment'], $request);
			//Uploading attachments has to run before form validation
			$uploadedIds = $this->uploadAttachmentWithoutParentId($request);
		} catch (Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 1);
		}
		try {
			$request->validate($this->getValidationRules());
			$this->postValidationForDateTime($request, $props);
		} catch (ValidationException $e) {
			if ($request['tableNames'] == 'fakeRequest') {
				$newValidation = $this->createTableValidator($e, $request);
				return redirect("")->withErrors($newValidation)->withInput();
			}
			throw $e; //<<This is for form's fields
		}
		try {
			//Get newStatus before it get removed by handleFields
			$newStatus = $request['status'];
			$fields = $this->handleFields($request, __FUNCTION__);
			$fields = $this->autoDocIDGeneration($fields);
			$theRow = $this->data::create($fields);
			$objectType = Str::modelPathFrom($theRow->getTable());
			$objectId = $theRow->id;
			$this->updateAttachmentParentId($uploadedIds, $objectType, $objectId);
			$this->attachOrphan($props['attachment'], $request, $objectType, $objectId);
			$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);
		} catch (Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 2);
		}
		$toastrResult =  ($request['tableNames'] !== 'fakeRequest') ? $this->handleEditableTables($request, $props['editable_table']) : [];
		try {
			$this->handleStatus($theRow, $newStatus);
		} catch (Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 3);
		}
		if ($request['tableNames'] === 'fakeRequest') {
			$this->dump1("Created line ", $theRow->id, __LINE__);
			return $theRow->id;
		}
		if ($this->debugForStoreUpdate) dd(__FUNCTION__ . " done");
		$this->handleToastrMessage(__FUNCTION__, $toastrResult);
		//Fire the event "Created New Document"
		$this->eventCreatedNotificationAndMail($fields, $theRow->id, $newStatus, $this->type);
		return redirect(route(Str::plural($this->type) . ".edit", $theRow->id));
	}

	public function update(Request $request, $id)
	{
		// dump($request->input());
		// dump($request->files);
		// dd();
		// if ($request['tableNames'] == 'fakeRequest') {
		// 	dump($request->input());
		// }
		try {
			$this->dump1("Request", $request->input(), __LINE__);
			$props = $this->getProps1();
			$this->deleteAttachments($props['attachment'], $request);
			//Uploading attachments has to run before form validation
			$uploadedIds = $this->uploadAttachmentWithoutParentId($request);
		} catch (Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 1);
		}
		try {
			$request->validate($this->getValidationRules());
			$this->postValidationForDateTime($request, $props);
		} catch (ValidationException $e) {
			if ($request['tableNames'] == 'fakeRequest') {
				$newValidation = $this->createTableValidator($e, $request);
				return redirect("")->withErrors($newValidation)->withInput();
			}
			throw $e; //<<This is for form's fields
		}
		try {
			//Get newStatus before it get removed by handleFields

			$newStatus = $request['status'];
			$fields = $this->handleFields($request, __FUNCTION__);
			$fields = $this->addEntityType($fields, 'status', $newStatus);
			$theRow = $this->data::find($id);
			$previousValue = $this->getPreviousValue($fields, $theRow);
			$theRow->fill($fields);
			$theRow->save();
			$objectType = Str::modelPathFrom($theRow->getTable());
			$objectId = $theRow->id;

			$this->updateAttachmentParentId($uploadedIds, $objectType, $objectId);
			$this->attachOrphan($props['attachment'], $request, $objectType, $objectId);

			$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);
		} catch (Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 2);
		}
		$toastrResult = ($request['tableNames'] !== 'fakeRequest') ? $this->handleEditableTables($request, $props['editable_table']) : [];
		try {
			$this->handleStatus($theRow, $newStatus);
		} catch (Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 3);
		}
		if ($request['tableNames'] === 'fakeRequest') {
			$this->dump1("Updated line ", $theRow->id, __LINE__);
			return $theRow->id;
		}
		if ($this->debugForStoreUpdate) dd(__FUNCTION__ . " done");
		$this->handleToastrMessage(__FUNCTION__, $toastrResult);
		//Fire the event "Updated New Document"
		$this->eventUpdatedNotificationAndMail($previousValue, $fields, $this->type, $newStatus);
		return redirect(route(Str::plural($this->type) . ".edit", $theRow->id));
	}

	public function storeEmpty(Request $request)
	{
		$theRow = $this->data::create($request->input());
		return ResponseObject::responseSuccess([['id' => $theRow->id]]);
	}

	public function updateShort(Request $request, $id)
	{
		$theRow = $this->data::find($id);
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
