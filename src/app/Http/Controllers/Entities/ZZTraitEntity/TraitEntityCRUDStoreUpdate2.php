<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait TraitEntityCRUDStoreUpdate2
{
	use TraitEntityFieldHandler2;
	use TraitEntityAttachment2;
	use TraitEntityEditableTable;
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
		// dd($request->input());
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
			//Get newStatus before it get removed by handleFields
			$newStatus = $request['status'];
			$rules = $this->getValidationRules($newStatus, __FUNCTION__);
			$request->validate($rules);
			$this->postValidationForDateTime($request, $props);
		} catch (ValidationException $e) {
			if ($request['tableNames'] == 'fakeRequest') {
				$newValidation = $this->createTableValidator($e, $request);
				return redirect("")->withErrors($newValidation)->withInput();
			}
			throw $e; //<<This is for form's fields
		}
		try {
			$fields = $this->handleFields($request, __FUNCTION__);
			// dd($fields);
			$theRow = $this->data::create($fields);
			$objectType = Str::modelPathFrom($theRow->getTable());
			$objectId = $theRow->id;
			if ($uploadedIds) {
				$this->updateAttachmentParentId($uploadedIds, $objectType, $objectId);
			}
			$this->attachOrphan($props['attachment'], $request, $objectType, $objectId);
			$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);
		} catch (Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 2);
		}
		$toastrResult = [];
		if ($request['tableNames'] !== 'fakeRequest') {
			[$toastrResult] = $this->handleEditableTables($request, $props['editable_table'], $objectId);
		}
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
		$this->eventCreatedNotificationAndMail($fields, $theRow->id, $newStatus, $this->type, $this->data);
		return redirect(route(Str::plural($this->type) . ".edit", $theRow->id));
	}

	public function update(Request $request, $id)
	{
		// dd($request->input());
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
			//Get newStatus before it get removed by handleFields
			$theRow = $this->data::find($id);
			$oldStatus = $theRow['status'];
			$newStatus = $request['status'];
			$rules = $this->getValidationRules($oldStatus, __FUNCTION__);
			// if ($request['tableNames'] == 'fakeRequest') Log::info($rules);
			if ($request['tableNames'] !== 'fakeRequest') {
				$this->makeUpTableFieldForRequired($request);
			}
			$this->makeUpCommentFieldForRequired($request);
			$request->validate($rules);
			$this->postValidationForDateTime($request, $props);
		} catch (ValidationException $e) {
			if ($request['tableNames'] == 'fakeRequest') {
				$newValidation = $this->createTableValidator($e, $request);
				return redirect("")->withErrors($newValidation)->withInput();
			}
			throw $e; //<<This is for form's fields
		}
		try {
			$fields = $this->handleFields($request, __FUNCTION__);
			$fieldForEmailHandler = $this->addEntityType($fields, 'status', $newStatus);
			$previousValue = $this->getPreviousValue($fieldForEmailHandler, $theRow);
			$theRow->updateWithOptimisticLocking($fields);
			$objectType = Str::modelPathFrom($theRow->getTable());
			$objectId = $theRow->id;
			if ($uploadedIds) {
				$this->updateAttachmentParentId($uploadedIds, $objectType, $objectId);
			}
			$this->attachOrphan($props['attachment'], $request, $objectType, $objectId);
			$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);
		} catch (Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 2);
		}

		$toastrResult = [];
		$lineResult = true;
		if ($request['tableNames'] !== 'fakeRequest') {
			[$toastrResult, $lineResult] = $this->handleEditableTables($request, $props['editable_table'], $objectId);
		}
		try {
			//If all tables are created or updated, change the status of the item
			if ($lineResult) $this->handleStatus($theRow, $newStatus);
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
		$this->eventUpdatedNotificationAndMail($previousValue, $fieldForEmailHandler, $this->type, $newStatus, $this->data);
		return redirect(route(Str::plural($this->type) . ".edit", $theRow->id));
	}
}
