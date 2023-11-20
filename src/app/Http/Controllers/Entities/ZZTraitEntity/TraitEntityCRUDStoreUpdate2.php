<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\System\Timer;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
	// use TraitEventInspChklst;
	use TraitEntityUpdateUserSettings;
	use TraitUpdatedProdSequenceEvent;
	use TraitHelperRedirect;
	use TraitCloneRequest;

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
			// $this->postValidationForDateTime($request, $props);//<< Removed since flatpickr
		} catch (ValidationException $e) {
			if ($request['tableNames'] == 'fakeRequest') {
				$newValidation = $this->createTableValidator($e, $request);
				return redirect("")->withErrors($newValidation)->withInput();
			}
			throw $e; //<<This is for form's fields
		}
		try {
			$fields = $this->handleFields($request, __FUNCTION__);
			$theRow = $this->modelPath::create($fields);
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
			$this->handleStatus($theRow, $request, $newStatus);
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
		Timer::getTimeElapseFromLastAccess();
		$isFakeRequest = $request['tableNames'] == 'fakeRequest';
		// if (!$isFakeRequest) dump($request);
		// if (!$isFakeRequest) {
		// 	dump($request->input());
		// 	dump($request->files);
		// 	dd();
		// }
		$this->updateUserSettings($request);
		$this->reArrangeComments($request);

		try {
			$this->dump1("Request", $request->input(), __LINE__);
			$props = $this->getProps1();
			$this->deleteAttachments($props['attachment'], $request);
			//Uploading attachments has to run before form validation

			if (!$isFakeRequest) {
				$allTable01Names = array_keys($request->input('tableNames'));
				$requestWithoutAttachment = $this->cloneRequest($request);
				foreach ($allTable01Names as $tableName) {
					$requestWithoutAttachment->files->remove($tableName);
				}
				// dump("Uploading real request", $requestWithoutAttachment->files);
				$uploadedIds = $this->uploadAttachmentWithoutParentId($requestWithoutAttachment);
			} else {
				// dump("Uploading fake request", $request->files);
				$uploadedIds = $this->uploadAttachmentWithoutParentId($request);
			}
		} catch (\Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 1);
		}
		try {
			//Get newStatus before it get removed by handleFields
			$theRow = $this->modelPath::find($id);
			$objectType = Str::modelPathFrom($theRow->getTable());
			$objectId = $theRow->id;
			$oldStatus = $theRow['status'];
			$newStatus = $request['status'];

			//Move attachments, comments, signatures, and oracy here
			//As when table fail to validate, it will throw an exception and skip all of these
			if ($uploadedIds) {
				$this->updateAttachmentParentId($uploadedIds, $objectType, $objectId);
			}
			$this->attachOrphan($props['attachment'], $request, $objectType, $objectId);
			$this->processComments($request);
			$this->processSignatures($request);
			$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);

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
			// Log::info($rules);
			// dd();

			//START OF TABLE BLOCK
			//This handle table block is moved from the bottom to before validate fields
			//As if it was, the datetime picker would apply YYYY-MM-DD format and cause validation issues for the next submission
			$toastrResult = [];
			$lineResult = true;
			// dd($isFakeRequest);
			if (!$isFakeRequest) {
				[$toastrResult, $lineResult, $toBeOverrideAggregatedFields] = $this->handleEditableTables($request, $props['editable_table'], $theRow->id);
				// Log::info($toBeOverrideAggregatedFields);
			}
			//END OF TABLE BLOCK

			$request->validate($rules, ["date_format" => "The :attribute must be correct datetime format."]);
			// $this->postValidationForDateTime($request, $props); //<< Removed since flatpickr
			// if (!$isFakeRequest) {
			// 	dump($request->input());
			// 	dd();
			// }
		} catch (ValidationException $e) {
			if ($isFakeRequest) {
				$newValidation = $this->createTableValidator($e, $request);
				return redirect("")->withErrors($newValidation)->withInput();
			}
			throw $e; //<<This is for form's fields
		}
		try {
			$fields = $this->handleFields($request, __FUNCTION__);
			// Log::info($fields);
			$handledFields = $this->addEntityValue($fields, 'status', $newStatus);
			$previousValue = $this->getPreviousValue($handledFields, $theRow);
			$fieldForEmailHandler = $this->addEntityValue($handledFields, 'created_at', $theRow->getAttributes()['created_at']);
			$fieldForEmailHandler = $this->addEntityValue($fieldForEmailHandler, 'updated_at', $theRow->getAttributes()['updated_at']);
			//Fire the event "Send Mail give Monitors No and Comment"
			// $this->fireEventInspChklst($request, $id);
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
				$this->handleStatus($theRow, $request, $newStatus);
				if (isset($toBeOverrideAggregatedFields)) {
					//If there are aggregation fields, override them
					// Log::info($toBeOverrideAggregatedFields);
					// Log::info($handledFields);
					$handledFields = array_merge($handledFields, $toBeOverrideAggregatedFields);
					// Log::info($handledFields);
				}
				// $theRow->updateWithOptimisticLocking($handledFields);
				$theRow->fill($handledFields);
				$theRow->save();
			}
		} catch (ValidationException $e) {
			// dump($e->getMessage());
			Toastr::error($e->getMessage(), "Constraint failed");
			return $this->redirectCustomForUpdate2($request, $theRow);; // Skip status logger
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
		$this->removeAttachmentForFields($fieldForEmailHandler, $props['attachment'], $isFakeRequest, $allTable01Names);
		$this->eventUpdatedNotificationAndMail($previousValue, $fieldForEmailHandler, $newStatus, $toastrResult);
		// dump($previousValue, $fieldForEmailHandler);
		$this->emitPostUpdateEvent($theRow->id);
		Log::info(Timer::getTimeElapseFromLastAccess());
		return $this->redirectCustomForUpdate2($request, $theRow);
	}
}
