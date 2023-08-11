<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait TraitEntityCRUDStoreUpdate2
{
	use TraitEntityFieldHandler2;
	use TraitEntityAttachment2;
	use TraitEntityEditableTable;
	use TraitEntityEditableComment;
	use TraitEntityEditableSignature;
	use TraitValidation;
	use TraitSendNotificationAndMail;
	use TraitEventInspChklst;
	use TraitEntityUpdateUserSettings;
	use TraitUpdatedProdSequenceEvent;
	use TraitHelperRedirect;

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
		// dd(SuperProps::getFor($this->type));
		// dd($request->input());
		$this->reArrangeComments($request);

		try {
			$this->dump1("Request", $request->input(), __LINE__);
			$props = $this->getProps1();
			$this->deleteAttachments($props['attachment'], $request);
			//Uploading attachments has to run before form validation
			$uploadedIds = $this->uploadAttachmentWithoutParentId($request);
		} catch (\Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 1);
		}
		try {
			//Get newStatus before it get removed by handleFields
			$newStatus = $request['status'];
			/** oldStatus when create will be same as new status */
			$oldStatus = $newStatus;
			$rules = $this->getValidationRules($oldStatus, $newStatus, __FUNCTION__);

			$this->makeUpCommentFieldForRequired($request);
			$request->validate($rules, ["date_format" => "The :attribute must be correct datetime format."]);
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
			$theRow = $this->data::create($fields);
			$objectType = Str::modelPathFrom($theRow->getTable());
			$objectId = $theRow->id;
			if ($uploadedIds) {
				$this->updateAttachmentParentId($uploadedIds, $objectType, $objectId);
			}
			$this->processComments($request, $objectId);
			$this->attachOrphan($props['attachment'], $request, $objectType, $objectId);
			$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);
		} catch (\Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 2);
		}
		$toastrResult = [];
		if ($request['tableNames'] !== 'fakeRequest') {
			[$toastrResult] = $this->handleEditableTables($request, $props['editable_table'], $objectId);
		}
		try {
			$this->handleStatus($theRow, $newStatus);
		} catch (\Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 3);
		}
		if ($request['tableNames'] === 'fakeRequest') {
			$this->dump1("Created line ", $theRow->id, __LINE__);
			return $theRow->id;
		}
		// if ($this->debugForStoreUpdate)
		// dd(__FUNCTION__ . " done");
		$this->handleToastrMessage(__FUNCTION__, $toastrResult);
		//Fire the event "Created New Document"
		$this->eventCreatedNotificationAndMail($theRow->getAttributes(), $theRow->id, $newStatus, $toastrResult);
		return redirect(route(Str::plural($this->type) . ".edit", $theRow->id));
	}

	public function update(Request $request, $id)
	{
		// dd(SuperProps::getFor($this->type));
		// dump($this->type);
		// dd($request->input('saveAndClose'));
		// dump($request->files);
		// dd();
		// if ($request['tableNames'] == 'fakeRequest') {
		// 	dump($request->input());
		// }
		$this->updateUserSettings($request);
		$this->reArrangeComments($request);

		$isFakeRequest = $request['tableNames'] == 'fakeRequest';
		try {
			$this->dump1("Request", $request->input(), __LINE__);
			$props = $this->getProps1();
			$this->deleteAttachments($props['attachment'], $request);
			//Uploading attachments has to run before form validation
			$uploadedIds = $this->uploadAttachmentWithoutParentId($request);
		} catch (\Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 1);
		}
		try {
			//Get newStatus before it get removed by handleFields
			$theRow = $this->data::find($id);
			$oldStatus = $theRow['status'];
			$newStatus = $request['status'];
			// dump($oldStatus);
			// dump($newStatus);
			$rules = $this->getValidationRules($oldStatus, $newStatus, __FUNCTION__, $isFakeRequest);
			// if ($request['tableNames'] == 'fakeRequest') Log::info($rules);
			// if ($request['tableNames'] !== 'fakeRequest') 
			if (!$isFakeRequest) {
				$this->makeUpTableFieldForRequired($request);
			}
			$this->makeUpAttachmentFieldForRequired($theRow, $request);
			$this->makeUpCommentFieldForRequired($request);
			// dump($request);
			// dump($rules);
			// dd();

			//START OF TABLE BLOCK
			//This handle table block is moved from the bottom to before validate fields
			//As if it was, the datetime picker would apply YYYY-MM-DD format and cause validation issues for the next submission
			$toastrResult = [];
			$lineResult = true;
			if (!$isFakeRequest) {
				[$toastrResult, $lineResult] = $this->handleEditableTables($request, $props['editable_table'], $theRow->id);
			}
			//END OF TABLE BLOCK

			$request->validate($rules, ["date_format" => "The :attribute must be correct datetime format."]);
			$this->postValidationForDateTime($request, $props);
		} catch (ValidationException $e) {
			if ($isFakeRequest) {
				$newValidation = $this->createTableValidator($e, $request);
				return redirect("")->withErrors($newValidation)->withInput();
			}
			throw $e; //<<This is for form's fields
		}
		try {
			$fields = $this->handleFields($request, __FUNCTION__);
			$fieldsHandle = $this->addEntityType($fields, 'status', $newStatus);
			$previousValue = $this->getPreviousValue($fieldsHandle, $theRow);
			$fieldForEmailHandler = $this->addEntityType($fieldsHandle, 'created_at', $theRow->getAttributes()['created_at']);
			$fieldForEmailHandler = $this->addEntityType($fieldForEmailHandler, 'updated_at', $theRow->getAttributes()['updated_at']);
			$objectType = Str::modelPathFrom($theRow->getTable());
			$objectId = $theRow->id;
			if ($uploadedIds) {
				$this->updateAttachmentParentId($uploadedIds, $objectType, $objectId);
			}
			//Fire the event "Send Mail give Monitors No and Comment"
			$this->fireEventInspChklst($request, $id);
			$this->processComments($request);
			$this->processSignatures($request);
			$this->attachOrphan($props['attachment'], $request, $objectType, $objectId);
			$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);
		} catch (\Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 2);
		}

		//START OF TABLE BLOCK
		// $toastrResult = [];
		// $lineResult = true;
		// if (!$isFakeRequest) {
		// 	[$toastrResult, $lineResult] = $this->handleEditableTables($request, $props['editable_table'], $objectId);
		// }
		//END OF TABLE BLOCK

		try {
			//If all tables are created or updated, change the status of the item
			if ($lineResult) {
				$this->handleStatus($theRow, $newStatus);
				$theRow->updateWithOptimisticLocking($fieldsHandle);
			}
		} catch (\Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 3);
		}
		if ($isFakeRequest) {
			$this->dump1("Updated line ", $theRow->id, __LINE__);
			return $theRow->id;
		}
		// if ($this->debugForStoreUpdate) 
		// dd(__FUNCTION__ . " done");
		$this->handleToastrMessage(__FUNCTION__, $toastrResult);
		//Fire the event "Updated New Document"
		$this->removeAttachmentForFields($fieldForEmailHandler, $props['attachment']);
		$this->eventUpdatedNotificationAndMail($previousValue, $fieldForEmailHandler, $newStatus, $toastrResult);
		$this->eventUpdatedProdSequence($theRow->id);
		return $this->redirectCustomForUpdate2($request,$theRow);
	}
	
}
