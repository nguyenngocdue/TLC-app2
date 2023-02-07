<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Attachment;
use App\Utils\Support\Json\SuperProps;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait TraitEntityCRUDStoreUpdate2
{
	use TraitEntityEditableTable;

	private $debugForStoreUpdate = false;

	private function dump1($title, $content, $line)
	{
		if ($this->debugForStoreUpdate) {
			echo "$title line $line";
			dump($content);
		}
	}

	private function getProps1()
	{
		$result = [
			'oracy_prop' => [],
			'eloquent_prop' => [],
			'attachment' => [],
			'editable_table' => [],
		];
		foreach ($this->superProps['props'] as $prop) {
			if ($prop['control'] === 'attachment') {
				$column_type = 'attachment';
			} elseif ($prop['control'] === 'relationship_renderer') {
				$column_type = 'editable_table';
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
			$result[$column_type][] = $prop['name'];
		}
		$this->dump1("getProps1", $result, __LINE__);
		return $result;
	}

	private function getValidationRules()
	{
		$rules = [];
		foreach ($this->superProps['props'] as $prop) {
			if (isset($prop['default-values']['validation'])) {
				$rules[$prop['column_name']] = $prop['default-values']['validation'];
			}
		}
		$rules = array_filter($rules, fn ($i) => $i);
		$this->dump1("getValidationRules", $rules, __LINE__);
		return $rules;
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
		$dataSource = $this->applyFormula($dataSource);
		$dataSource = $this->handleToggle($dataSource);
		$dataSource = $this->handleTextArea($dataSource);

		$this->dump1("handleFields", $dataSource, __LINE__);
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

	protected function handleMyException($e, $action,)
	{
		dump("Exception during $action " . $e->getFile() . " line " . $e->getLine());
		dd($e->getMessage());
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
			$this->handleMyException($e, __FUNCTION__);
		}
		$request->validate($this->getValidationRules());
		try {
			//Get newStatus before it get removed by handleFields
			$newStatus = $request['status'];
			$fields = $this->handleFields($request, __FUNCTION__);

			$theRow = $this->data::create($fields);
			$objectType = Str::modelPathFrom($theRow->getTable());
			$objectId = $theRow->id;

			$this->updateAttachmentParentId($uploadedIds, $objectType, $objectId);
			$this->attachOrphan($props['attachment'], $request, $objectType, $objectId);

			$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);
		} catch (Exception $e) {
			$this->handleMyException($e, __FUNCTION__);
		}
		if ($request['tableNames'] !== 'fakeRequest') $this->handleEditableTables($request, $props['editable_table']);
		try {
			$this->handleStatus($theRow, $newStatus);
		} catch (Exception $e) {
			$this->handleMyException($e, __FUNCTION__);
		}
		if ($request['tableNames'] === 'fakeRequest') return;
		if ($this->debugForStoreUpdate) dd(__FUNCTION__ . " done");
		Toastr::success("$this->type created successfully", "Create $this->type");
		return redirect(route(Str::plural($this->type) . ".edit", $theRow->id));
	}

	public function update(Request $request, $id)
	{
		if ($request['tableNames'] === 'fakeRequest') {
			$tableName = $request['tableName'];
			$this->superProps = SuperProps::getFor($tableName);
			$this->data = Str::modelPathFrom($tableName);
		}
		try {
			$this->dump1("Request", $request->input(), __LINE__);
			$props = $this->getProps1();
			$this->deleteAttachments($props['attachment'], $request);
			//Uploading attachments has to run before form validation
			$uploadedIds = $this->uploadAttachmentWithoutParentId($request);
		} catch (Exception $e) {
			$this->handleMyException($e, __FUNCTION__);
		}
		$request->validate($this->getValidationRules());
		try {
			//Get newStatus before it get removed by handleFields
			$newStatus = $request['status'];
			$fields = $this->handleFields($request, __FUNCTION__);

			$theRow = $this->data::find($id);
			$theRow->fill($fields);
			$theRow->save();
			$objectType = "App\\Models\\" . Str::singular($theRow->getTable());
			$objectId = $theRow->id;

			$this->updateAttachmentParentId($uploadedIds, $objectType, $objectId);
			$this->attachOrphan($props['attachment'], $request, $objectType, $objectId);

			$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);
		} catch (Exception $e) {
			$this->handleMyException($e, __FUNCTION__);
		}
		if ($request['tableNames'] !== 'fakeRequest') $this->handleEditableTables($request, $props['editable_table']);
		try {
			$this->handleStatus($theRow, $newStatus);
		} catch (Exception $e) {
			$this->handleMyException($e, __FUNCTION__);
		}
		if ($request['tableNames'] === 'fakeRequest') return;
		if ($this->debugForStoreUpdate) dd(__FUNCTION__ . " done");
		Toastr::success("$this->type updated successfully", "Updated $this->type");
		return redirect(route(Str::plural($this->type) . ".edit", $theRow->id));
	}
}
