<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\BigThink\Oracy;
use App\Events\CreatedDocumentEvent2;
use App\Events\UpdatedDocumentEvent;
use App\Http\Controllers\ExamQuestion\ExamQuestionController;
use App\Http\Services\LoggerForTimelineService;
use App\Utils\Support\CurrentUser;
use App\Utils\System\Timer;
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
	// use TraitSendNotificationAndMail;
	// use TraitEventInspChklst;
	use TraitEntityDiff;
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
		if ($this->type == 'exam_sheet') {
			$controller = new ExamQuestionController($request);
			$response = $controller->store($request, $this->type);
			return $response;
		}
		// dd(SuperProps::getFor($this->type));
		// dd($request->input());
		$this->reArrangeComments($request);

		try {
			$this->dump1("Request", $request->input(), __LINE__);
			$props = $this->getProps1(null);
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
			$modelPath = Str::modelPathFrom($theRow->getTable());
			$objectId = $theRow->id;
			if ($uploadedIds) {
				$this->updateAttachmentParentId($uploadedIds, $modelPath, $objectId);
			}
			$this->processComments($request, $objectId);
			$this->attachOrphan($props['attachment'], $request, $modelPath, $objectId);
			$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);
			$this->handleCheckboxAndDropdownMulti2a($request, $theRow, $props['many_to_many']);
		} catch (\Exception $e) {
			$this->handleMyException($e, __FUNCTION__, 2);
		}
		$toastrResult = [];
		if ($request['tableNames'] !== 'fakeRequest') {
			[$toastrResult] = $this->handleEditableTables($request, $props['editable_table'], [], $objectId);
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
		// $this->eventCreatedNotificationAndMail($theRow->getAttributes(), $theRow->id, $newStatus, $toastrResult);
		(new LoggerForTimelineService())->insertForCreate($theRow, CurrentUser::id(), $this->modelPath);
		event(new CreatedDocumentEvent2($this->type, $theRow->id));
		return redirect(route(Str::plural($this->type) . ".edit", $theRow->id));
	}

	private function attachMonitors(&$array, $item)
	{
		if (method_exists($item, "getMonitors1")) {
			$values = $item->getMonitors1()->pluck('users.id')->toArray();
			$array['getMonitors1()'] = $values;
		}
		if (method_exists($item, "getMonitors2")) {
			$values = $item->getMonitors2()->pluck('users.id')->toArray();
			$array['getMonitors2()'] = $values;
		}
	}

	public function update(Request $request, $id, $getManyLineParams = null)
	{
		// dd($request);
		if ($this->type == 'exam_sheet') {
			$controller = new ExamQuestionController($request);
			$response = $controller->update($request, $this->type, $id);
			return $response;
		}

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

		$previousItem = null;
		//This is to compare status, assignee, and monitors for sending MailChangeStatus
		if (!$isFakeRequest) {
			$previousItem = $this->modelPath::find($id);
			// if ($this->type == 'qaqc_insp_chklst_sht') $diff = $this->getDiff($previousItem, $request);
			Oracy::attach("getMonitors1()", [$previousItem], true);
			$previousValue = $previousItem->getAttributes();
			// $previousValue = $this->getPreviousValue($currentValue, $theRow);
			// dump($previousValue);
		}

		try {
			$this->dump1("Request", $request->input(), __LINE__);
			$props = $this->getProps1($previousItem);
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
			$statusless = $this->modelPath::$statusless;
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
			if (!$isFakeRequest) {
				$this->handleCheckboxAndDropdownMulti2a($request, $theRow, $props['many_to_many']);
			} else {
				$this->handleCheckboxAndDropdownMulti2a($request, $theRow, $props['many_to_many'], $getManyLineParams);
			}
			if (!$isFakeRequest) {
				//This will stop Project update keep deleting the sub project routings
				$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);
			} else {
				$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop'], $getManyLineParams);
			}

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
				[$toastrResult, $lineResult, $toBeOverrideAggregatedFields] = $this->handleEditableTables($request, $props['editable_table'], $props['editable_table_get_many_line_params'], $theRow->id);
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
			$currentValue = $this->handleFields($request, __FUNCTION__);
			if (!$statusless) {
				$currentValue['status'] = $newStatus;
			}
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
					// Log::info($currentValue);
					$currentValue = array_merge($currentValue, $toBeOverrideAggregatedFields);
					// Log::info($currentValue);
				}
				$theRow->fill($currentValue);
				$theRow->save();
			}
		} catch (ValidationException $e) {
			// dump($e->getMessage());
			toastr()->error($e->getMessage(), "Constraint failed");
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
		$this->removeAttachmentForFields($currentValue, $props['attachment'], $isFakeRequest, $allTable01Names);
		$this->attachMonitors($currentValue, $theRow);
		(new LoggerForTimelineService())->insertForUpdate($theRow, $previousValue, CurrentUser::id(), $this->modelPath);
		event(new UpdatedDocumentEvent($previousValue, $currentValue, $this->type, $this->modelPath, CurrentUser::id()));
		$this->emitPostUpdateEvent($theRow->id, $request);
		Log::info($this->type . " update2 elapsed ms: " . Timer::getTimeElapseFromLastAccess());
		return $this->redirectCustomForUpdate2($request, $theRow);
	}
}
