<?php

namespace App\Http\Controllers\Entities;

use App\Events\EntityCreatedEvent;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Services\ReadingFileService;
use App\Http\Services\UploadService;
use App\Http\Traits\HasStatus;
use App\Notifications\CreateNewNotification;
use App\Notifications\EditNotification;
use App\Utils\Support\Props;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

abstract class AbstractCreateEditController extends Controller
{
	use CreateEditControllerM2M;
	use CreateEditControllerMedia;
	use CreateEditControllerComment;
	use CreateEditControllerStatus;
	use CreateEditFormula;
	// use HasStatus;

	protected $type;
	protected $data;
	protected $action;
	protected $branchName = 'entities';
	protected $disk = 'json';
	protected $r_fileName = 'props.json';
	public function __construct(protected UploadService $uploadService, protected ReadingFileService $readingFileService)
	{
	}

	public function getType()
	{
		return $this->type;
	}

	public function create()
	{
		$action = $this->action;
		$props = Props::getAllOf($this->type);

		if ($props  === false) {
			$title = "Setting is missing";
			$type = 'warning';
			$message =  "File \"$this->r_fileName\" is missing. <br/>Please create this file by \"manage prop\".";
			return view('components.feedback.result')->with(compact('message', 'title', 'type'));
		}

		$type = $this->type;
		$modelPath = $this->data;
		$values = "";
		$idItems = [];
		return view('dashboards.pages.createEdit')->with(compact('props', 'type', 'action', 'modelPath', 'values', 'idItems'));
	}

	public function edit($id)
	{
		$currentElement = $this->data::find($id);
		$props = Props::getAllOf($this->type);
		$type = Str::plural($this->type);
		$action = $this->action;
		$values = $action === "create" ? "" : $currentElement;

		$modelPath = $this->data;

		$idItems = $this->getManyToManyRelationship($currentElement);
		// dd(123);
		return view('dashboards.pages.createEdit')->with(compact('props', 'values', 'type', 'action', 'currentElement', 'modelPath', 'idItems'));
	}

	public function store(Request $request)
	{
		$props = Props::getAllOf($this->type);
		$colNamesHaveAttachment = Helper::getColNamesByControlAndColumnType($props, 'attachment', 'string');
		$arrayExcept = array_merge(['_token', '_method', 'created_at', 'updated_at', 'id'], $colNamesHaveAttachment);
		$dataInput =  array_merge(['id' => null], $request->except($arrayExcept));

		// dd($request->all());
		$deletedMediaIds = $this->deleteMediaIfNeeded($dataInput);
		$idsMedia = $this->saveAndGetIdsMedia($request, $dataInput);

		// dd($idsMedia);
		$dataInput = $this->apply_formula($dataInput, $this->type);


		$comments = Helper::getAndChangeKeyItemsContainString($dataInput, 'newComment_');
		$request->merge($dataInput + $comments);
		$this->_validate($props, $request);

		$idsComment = $this->saveAndGetIdsComments($dataInput);
		$this->setMediaCommentsParent($idsComment, $idsMedia);


		$newDataInput = $this->handleToggle('store', $props, $dataInput);
		$newDataInput = $this->handleTextArea($props, $newDataInput);

		$newDataInputHasArray = array_filter($newDataInput, fn ($item) => is_array($item));
		$newDataInputNotArray = array_filter($newDataInput, fn ($item) => !is_array($item));
		// dd($newDataInputNotArray);

		try {
			$newItem = $this->data::create($newDataInputNotArray);
			$this->setStatus($newDataInput, $newItem);

			$_data = $this->data::find($newItem->id);

			$this->setCommentsParent($idsComment, $newItem);


			event(new EntityCreatedEvent(['id' => $newItem->id, 'type' => $this->type]));
			Notification::send($newItem, new CreateNewNotification($newItem->id));

			if (isset($newItem)) {
				$this->syncManyToManyToDB($newItem, $newDataInputHasArray); // Check box

				// $this->syncManyToManyRelationship($newItem, $newDataInputHasArray); // Check box

				// $event = event(new SendEmailItemCreated(['id' => $data->id, 'type' => $this->type]));
				// dd($event);

				if ($idsMedia) {
					$this->setMediaParent($newItem, $colNamesHaveAttachment);
					$this->updateMediaIdsToDBFields($_data, $colNamesHaveAttachment);
				}

				Toastr::success("$this->type created successfully", "Create $this->type");
			}

			$type = Str::plural($this->type);
			return redirect(route("{$type}.edit", $newItem->id));
		} catch (Exception $e) {
			dd($e->getMessage());
		};
	}

	public function update(Request $request, $id)
	{
		$data = $this->data::find($id);
		$props = Props::getAllOf($this->type);

		// dd($request->all());
		$colNamesHaveAttachment = Helper::getColNamesByControlAndColumnType($props, 'attachment', 'string');
		$arrayExcept = array_merge(['_token', '_method', 'created_at', 'updated_at'], $colNamesHaveAttachment);
		$dataInput = $request->except($arrayExcept);
		// dd($dataInput);

		$this->deleteMediaIfNeeded($dataInput);
		$idsMedia = $this->saveAndGetIdsMedia($request, $dataInput);
		$this->setMediaParent($data, $colNamesHaveAttachment);

		$dataInput = $this->apply_formula($dataInput, $this->type);

		$this->delComments($dataInput);

		$comments = Helper::getAndChangeKeyItemsContainString($dataInput, 'newComment_');
		$request->merge($dataInput + $comments);
		$this->_validate($props, $request);

		$newDataInput = $this->handleToggle('update', $props, $dataInput);
		$newDataInput = $this->handleTextArea($props, $newDataInput);

		// dd($dataInput);
		$idsComment = $this->saveAndGetIdsComments($newDataInput);
		$this->setCommentsParent($idsComment, $data);
		$this->setMediaCommentsParent($idsComment, $idsMedia);;


		$this->setStatus($newDataInput, null);
		try {
			$data->fill($newDataInput);
			$isSaved = $data->save();

			event(new EntityCreatedEvent(['id' => $data->id, 'type' => $this->type]));
			Notification::send($data, new EditNotification($data->id));

			if ($isSaved) {
				$this->syncManyToManyToDB($data, $dataInput); // Check box

				if ($idsMedia) {
					//set Media Parent is in saveMedia
					$this->updateMediaIdsToDBFields($data, $colNamesHaveAttachment);
				}

				Toastr::success("$this->type updated successfully", "Update $this->type");
			}

			$type = Str::plural($this->type);
			return redirect(route("{$type}.edit", $id));
		} catch (Exception $e) {
			dd($e->getMessage());
		}
	}

	private function _validate($props, Request $request)
	{
		$itemValidations = [];
		foreach ($props as $value) {
			if (!is_null($value['validation'])) $itemValidations[$value['column_name']] = $value['validation'];
		}
		$request->validate($itemValidations);
	}

	private function handleToggle($method, $props, &$dataInput)
	{
		$toggleControls = array_filter($props, fn ($prop) => $prop['control'] === 'toggle');
		$toggleControls = array_values(array_map(fn ($item) => $item['column_name'], $toggleControls));
		// dd($toggleControls);
		foreach ($toggleControls as $toggle) {
			$dataInput[$toggle] = isset($dataInput[$toggle]) ? ($method === 'store' ? $dataInput[$toggle] : 1)  : null;
		}

		return $dataInput;
	}

	private function handleTextArea($props, $newDataInput)
	{
		$colNameHasTextarea  = Helper::getColNamesByControlAndColumnType($props, 'textarea', 'json');
		if (count($colNameHasTextarea) > 0) $newDataInput = $this->modifyValueTextArea($colNameHasTextarea, $newDataInput);
		return $newDataInput;
	}

	private function updateMediaIdsToDBFields($data, $colNamesHaveAttachment)
	{
		$morphManyMediaUser = $data->media()->select('id', 'category')->get()->toArray();
		$media_cateTb = DB::table('fields')->select('id', 'name')->get()->toArray();

		$ids_names_cateMediaDB = array_column($media_cateTb, 'name', 'id');
		$idsMedia_idsCateName_User = array_column($morphManyMediaUser, 'category', 'id');

		$idsHasAttachMent = array_values(array_unique($idsMedia_idsCateName_User));

		$names_val_fields = [];
		foreach ($idsMedia_idsCateName_User as $key => $value) {
			foreach ($idsHasAttachMent as $cate) {
				if ($value === $cate) {
					$names_val_fields[$ids_names_cateMediaDB[$cate]][] = $key;
					break;
				}
			}
		}

		$valFields = array_map(fn ($item) => $item = implode(",", $item), $names_val_fields);
		foreach ($colNamesHaveAttachment as $attach) {
			if (!isset($valFields[$attach])) {
				$valFields[$attach] = null;
			}
		}
		$data->fill($valFields);
		$data->save();
	}

	private function modifyValueTextArea($colNameHasTextarea, $newDataInput)
	{
		$newTextAreas = [];
		foreach ($colNameHasTextarea as $_colName) {
			$object = $newDataInput[$_colName];
			$newTextAreas[$_colName] = json_decode(preg_replace("/\r|\n/", "", $object));
		}
		return array_replace($newDataInput, $newTextAreas);
	}
}
