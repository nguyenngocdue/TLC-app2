<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Events\CreateNewDocumentEvent;
use App\Events\UpdatedDocument;
use App\Events\UpdatedDocumentEvent;
use App\Http\Controllers\Workflow\LibApps;
use App\Models\Attachment;
use App\Utils\Constant;
use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\JsonControls;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait TraitEntityCRUDStoreUpdate2
{
	use TraitEntityEditableTable;
	use TraitEntityFormula;
	use TraitValidation;

	private $debugForStoreUpdate = false;

	private function dump1($title, $content, $line)
	{
		if ($this->debugForStoreUpdate) {
			echo "$title in file " . basename(__FILE__) . " line $line";
			dump($content);
		}
	}

	private function getProps1()
	{
		$table01Count = 0;
		$table01Index = "";
		$result = [
			'oracy_prop' => [],
			'eloquent_prop' => [],
			'attachment' => [],
			'datetime' => [],
			'editable_table' => [],
		];
		$dateTimeControls = ['picker_time', 'picker_date', 'picker_month', 'picker_week', 'picker_quarter', 'picker_year', 'picker_datetime'];
		foreach ($this->superProps['props'] as $prop) {
			if ($prop['hidden_edit']) continue;
			if (in_array($prop['control'], $dateTimeControls)) {
				$column_type = 'datetime';
				$control_sub_type = $prop['control'];
			} elseif ($prop['control'] === 'attachment') {
				$column_type = 'attachment';
			} elseif ($prop['control'] === 'relationship_renderer') {
				$column_type = 'editable_table';
				$table01Count++;
				$table01Index = "table" . str_pad($table01Count, 2, '0', STR_PAD_LEFT);
			} else {
				switch ($prop['column_type']) {
					case 'oracy_prop':
					case 'eloquent_prop':
						$column_type = $prop['column_type'];
						break;
					default:
						$column_type = 'field';
						break;
				}
			}

			if ($prop['control'] === 'relationship_renderer') {
				$result[$column_type][$table01Index] = $prop['name'];
			} elseif (in_array($prop['control'], $dateTimeControls)) {
				// if (!in_array($prop['name'], ['created_at', 'updated_at'])) {
				$result[$column_type][$control_sub_type][] = $prop['name'];
				// }
			} else {
				$result[$column_type][] = $prop['name'];
			}
		}
		$this->dump1("getProps1", $result, __LINE__);
		return $result;
	}

	private function handleToggle($dataSource)
	{
		foreach ($this->superProps['props'] as $prop) {
			if ($prop['control'] === 'toggle') {
				$column_name = $prop['column_name'];
				$dataSource[$column_name] = isset($dataSource[$column_name]);
			}
		}
		return $dataSource;
	}

	private function handleTextArea($dataSource)
	{
		foreach ($this->superProps['props'] as $prop) {
			if ($prop['control'] === 'textarea' && $prop['column_type'] === 'json') {
				$column_name = $prop['column_name'];
				$text = $dataSource[$column_name];
				$dataSource[$column_name] = /*json_decode*/ (preg_replace("/\r|\n/", "", $text));
			}
		}
		return $dataSource;
	}

	private function handleStatus($theRow, $newStatus)
	{
		if (!$newStatus) return;
		$theRow->transitionTo($newStatus);
	}

	private function handleCheckboxAndDropdownMulti(Request $request, $theRow, array $oracyProps)
	{
		foreach ($oracyProps as $prop) {
			$relatedModel = $this->superProps['props'][$prop]['relationships']['oracyParams'][1];
			$propName = substr($prop, 1); //Remove first "_"
			$ids = $request->input($propName);
			if (is_null($ids)) $ids = []; // Make sure it sync when unchecked all
			$ids = array_map(fn ($id) => $id * 1, $ids);

			$fieldName = substr($propName, 0, strlen($propName) - 2); //Remove parenthesis ()
			$theRow->syncCheck($fieldName, $relatedModel, $ids);
			$this->dump1("handleCheckboxAndDropdownMulti $propName", $ids, __LINE__);
		}
	}

	private function handleFields(Request $request, $action)
	{
		$dataSource = $request->except(['_token', '_method', 'status', 'created_at', 'updated_at']);
		if ($action === 'store') $dataSource['id'] = null;

		$this->dump1("Before handleFields", $dataSource, __LINE__);
		$dataSource = $this->applyFormula($dataSource);
		$dataSource = $this->handleToggle($dataSource);
		$dataSource = $this->handleTextArea($dataSource);

		$this->dump1("After handleFields", $dataSource, __LINE__);
		return $dataSource;
	}

	private function deleteAttachments($props, Request $request)
	{
		foreach ($props as $prop) {
			$propName = substr($prop, 1); //Remove first "_"
			$input = $request->input($propName);
			if (isset($input['toBeDeleted'])) {
				$toBeDeletedIds = Str::parseArray($input['toBeDeleted']);
				$this->uploadService2->destroy($toBeDeletedIds);
			} else {
				//This type doesn't have any attachment
			}
		}
	}

	private function attachOrphan($props, Request $request, $objectType, $objectId)
	{
		foreach ($props as $prop) {
			$propName = substr($prop, 1); //Remove first "_"
			$attachmentField = $request->input($propName);
			if (isset($attachmentField['toBeAttached'])) {
				$toBeAttachedIds = $attachmentField['toBeAttached'];
				// $this->uploadService2->destroy($toBeAttachedIds);
				// dd($toBeAttachedIds);
				foreach ($toBeAttachedIds as $id) {
					$attachment = Attachment::find($id);
					//In case if the orphan is just deleted in this transaction, ignore it from attaching
					if (!is_null($attachment)) {
						$attachment->object_type = $objectType;
						$attachment->object_id = $objectId;
						$attachment->save();
					}
				}
			}
		}
	}

	private function uploadAttachmentWithoutParentId(Request $request)
	{
		return $this->uploadService2->store($request);
	}

	private function updateAttachmentParentId($uploadedIds, $objectType, $objectId)
	{
		foreach (array_keys($uploadedIds) as $id) {
			$attachmentRow = Attachment::find($id);
			$attachmentRow->update(['object_type' => $objectType, 'object_id' => $objectId]);
		}
	}

	protected function handleMyException($e, $action, $phase)
	{
		dump("Exception during $action phase $phase " . $e->getFile() . " line " . $e->getLine());
		dd($e->getMessage());
		// dd($e);
	}

	private function handleToastrMessage($action, $toastrResult)
	{
		Toastr::success("$this->type $action successfully", "$action $this->type");
		if (!empty($toastrResult)) {
			foreach ($toastrResult as $table01Name => $toastrMessage) {
				Toastr::error($toastrMessage, "$table01Name $action failed");
			}
		}
	}

	private function postValidationForDateTime(Request &$request, $props)
	{
		$newRequest = $request->input();
		$dateTimeProps = $props['datetime'];
		foreach ($dateTimeProps as $subType => $controls) {
			foreach ($controls as $control) {
				$propName = substr($control, 1); //Remove first "_"
				if (in_array($subType, JsonControls::getDateTimeControls())) {
					if (isset($newRequest[$propName])) {
						// dump($subType, $propName, $newRequest[$propName]);
						$newRequest[$propName] = DateTimeConcern::convertForSaving($subType, $newRequest[$propName]);
					}
				}
			}
		}
		$request->replace($newRequest);
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
		//Add Id into array fields
		$fields = $this->addEntityType($fields, 'id', $theRow->id);
		event(new CreateNewDocumentEvent($currentValue = $this->addEntityType($fields, 'entity_type', $this->type)));
		return redirect(route(Str::plural($this->type) . ".edit", $theRow->id));
	}

	public function update(Request $request, $id)
	{
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
			$theRow = $this->data::find($id);
			$previousValue = [];
			foreach ($fields as $key => $value) {
				if ($key !== 'tableNames') {
					if (isset($theRow->$key)) {
						$previousValue[$key] = $theRow->$key;
					} else {
						if ($key === 'getMonitors()') {
							$valueGetMonitors = $theRow->getMonitors()->pluck('id')->toArray();
							$previousValue[$key] = $valueGetMonitors;
						}
					}
				} else {
					$previousValue[$key] = $value;
				}
			}
			$theRow->fill($fields);
			$theRow->save();
			$objectType = "App\\Models\\" . Str::singular($theRow->getTable());
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
		event(new UpdatedDocumentEvent(
			$previousValue = $this->addEntityType($previousValue, 'entity_type', $this->type),
			$currentValue = $this->addEntityType($fields, 'entity_type', $this->type)
		));
		return redirect(route(Str::plural($this->type) . ".edit", $theRow->id));
	}
	private function addEntityType($array, $key, $value)
	{
		$array[$key] = $value;
		return $array;
	}
	private function autoDocIDGeneration($fields)
	{
		$libAppsData = LibApps::getFor($this->type);
		if ($nameColumnDocIDFormat = $libAppsData['doc_id_format_column']) {
			$tableName = Str::plural($this->type);
			$maxDocID = DB::table($tableName)->where($nameColumnDocIDFormat, $fields[$nameColumnDocIDFormat])->max('doc_id');
			$fields['doc_id'] = $maxDocID + 1;
		}
		return $fields;
	}
}
