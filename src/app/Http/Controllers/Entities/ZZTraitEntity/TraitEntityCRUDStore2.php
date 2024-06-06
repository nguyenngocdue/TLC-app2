<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Events\CreatedDocumentEvent2;
use App\Http\Controllers\ExamQuestion\ExamQuestionController;
use App\Http\Services\LoggerForTimelineService;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait TraitEntityCRUDStore2
{
	use TraitEntityFieldHandler2;
	use TraitEntityAttachment2;
	use TraitEntityEditableTable;
	use TraitEntityEditableComment;
	use TraitEntityEditableSignature;
	use TraitValidation;
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
			// $this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);
			$this->handleCheckboxAndDropdownMulti2a($request, $theRow, $props['belongsToMany']);
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
}
