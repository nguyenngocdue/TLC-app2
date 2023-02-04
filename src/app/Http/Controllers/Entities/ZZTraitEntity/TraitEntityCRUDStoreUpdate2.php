<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Attachment;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait TraitEntityCRUDStoreUpdate2
{
	private $debugForStoreUpdate = false;

	private function dump1($title, $content)
	{
		if ($this->debugForStoreUpdate) {
			echo "$title";
			dump($content);
		}
	}

	private function getProps1()
	{
		$result = [
			'oracy_prop' => [],
			'eloquent_prop' => [],
			'attachment' => [],
		];
		foreach ($this->superProps['props'] as $prop) {
			if ($prop['control'] === 'attachment') {
				$column_type = 'attachment';
			} else
				switch ($prop['column_type']) {
					case 'oracy_prop':
					case 'eloquent_prop':
						$column_type = $prop['column_type'];
						break;
					default:
						$column_type = 'field';
						break;
				}
			$result[$column_type][] = $prop['name'];
		}
		$this->dump1("getProps1", $result);
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
		$this->dump1("getValidationRules", $rules);
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

	private function handleStatus($theRow, $fields)
	{
		if (!isset($fields['status'])) return;
		if (!isset($theRow['status'])) return;
		$theRow->transitionTo($fields['status']);
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
			$this->dump1("handleCheckboxAndDropdownMulti $propName", $ids);
		}
	}

	private function handleFields(Request $request, $action)
	{
		$dataSource = $request->except(['_token', '_method', 'status', 'created_at', 'updated_at']);
		if ($action === 'store') $dataSource['id'] = null;
		$dataSource = $this->applyFormula($dataSource);
		$dataSource = $this->handleToggle($dataSource);
		$dataSource = $this->handleTextArea($dataSource);

		$this->dump1("handleFields", $dataSource);
		return $dataSource;
	}

	private function deleteAttachments($props, Request $request)
	{
		foreach ($props as $prop) {
			$propName = substr($prop, 1); //Remove first "_"
			$toBeDeletedIds = Str::parseArray($request->input($propName)['toBeDeleted']);
			$this->uploadService2->destroy($toBeDeletedIds);
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

	public function store(Request $request)
	{
		$this->dump1("Request", $request->input());
		$props = $this->getProps1();
		$this->deleteAttachments($props['attachment'], $request);
		//Uploading attachments has to run before form validation
		$uploadedIds = $this->uploadAttachmentWithoutParentId($request);
		$request->validate($this->getValidationRules());
		$fields = $this->handleFields($request, __FUNCTION__);

		$theRow = $this->data::create($fields);
		$objectType = Str::modelPathFrom($theRow->getTable());
		$objectId = $theRow->id;

		$this->updateAttachmentParentId($uploadedIds, $objectType, $objectId);
		$this->attachOrphan($props['attachment'], $request, $objectType, $objectId);

		$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);
		$this->handleStatus($theRow, $fields);

		if ($this->debugForStoreUpdate) dd(__FUNCTION__ . " done");
		Toastr::success("$this->type created successfully", "Create $this->type");
		return redirect(route(Str::plural($this->type) . ".edit", $theRow->id));
	}

	public function update(Request $request, $id)
	{
		$this->dump1("Request", $request->input());
		$props = $this->getProps1();
		$this->deleteAttachments($props['attachment'], $request);
		//Uploading attachments has to run before form validation
		$uploadedIds = $this->uploadAttachmentWithoutParentId($request);
		$request->validate($this->getValidationRules());
		$fields = $this->handleFields($request, __FUNCTION__);

		$theRow = $this->data::find($id);
		$theRow->fill($fields);
		$theRow->save();
		$objectType = "App\\Models\\" . Str::singular($theRow->getTable());
		$objectId = $theRow->id;

		$this->updateAttachmentParentId($uploadedIds, $objectType, $objectId);
		$this->attachOrphan($props['attachment'], $request, $objectType, $objectId);

		$this->handleCheckboxAndDropdownMulti($request, $theRow, $props['oracy_prop']);
		$this->handleStatus($theRow, $fields);

		if ($this->debugForStoreUpdate) dd(__FUNCTION__ . " done");
		Toastr::success("$this->type updated successfully", "Updated $this->type");
		return redirect(route(Str::plural($this->type) . ".edit", $theRow->id));
	}
}
