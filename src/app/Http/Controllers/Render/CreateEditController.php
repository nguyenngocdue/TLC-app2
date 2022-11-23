<?php

namespace App\Http\Controllers\Render;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Services\ReadingFileService;
use App\Http\Services\UploadService;
use App\Notifications\CreateNewNotification;
use App\Utils\Constant;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

abstract class CreateEditController extends Controller
{
	use CreateEditControllerM2M;
	use CreateEditControllerMedia;

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
		// session([Constant::ORPHAN_MEDIA => []]);
		$action = $this->action;
		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);

		if ($props  === false) {
			$title = "Setting is missing";
			$type = 'warning';
			$message =  "File \"$this->r_fileName\" is missing. <br/>Please create this file by \"manage prop\".";
			return view('components.feedback.result')->with(compact('message', 'title', 'type'));
		}

		$type = $this->type;
		$tablePath = $this->data;
		$values = "";
		$idItems = [];
		return view('dashboards.pages.createEdit')->with(compact('props', 'type', 'action', 'tablePath', 'values', 'idItems'));
	}

	public function edit($id)
	{
		$currentElement = $this->data::find($id);
		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
		$type = Str::plural($this->type);
		$action = $this->action;
		$values = $action === "create" ? "" : $currentElement;

		$tablePath = $this->data;

		$idItems = $this->getManyToManyRelationship($currentElement);
		return view('dashboards.pages.createEdit')->with(compact('props', 'values', 'type', 'action', 'currentElement', 'tablePath', 'idItems'));
	}

	public function store(Request $request)
	{
		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
		$colNamehasAttachment = Helper::getColNamebyControls($props, 'attachment');
		$arrayExcept = array_merge(['_token', '_method', 'created_at', 'updated_at'], $colNamehasAttachment);
		$dataInput = $request->except($arrayExcept);
		$type = Str::plural($this->type);



		$idsMediaDeleted = $this->deleteMediaIfNeeded($dataInput);

		$hasAttachment = $this->saveMediaValidator('store', $request, $dataInput, null, $idsMediaDeleted);

		$this->_validate($props, $request);

		$newDataInput = $this->handleToggle('store', $props, $dataInput);

		$newDataInputHasAttachment = array_filter($newDataInput, fn ($item) => is_array($item));
		$newDataInputNotAttachment = array_filter($newDataInput, fn ($item) => !is_array($item));

		try {
			$data = $this->data::create($newDataInputNotAttachment);
			Notification::send($data, new CreateNewNotification($data->id));

			if (isset($data)) {

				$this->syncManyToManyRelationship($data, $newDataInputHasAttachment); // Check box
				if ($hasAttachment) {
					$this->setMediaParent($data, $colNamehasAttachment);
					$_data = $this->data::find($data->id);
					$this->updateIdsMediaToFieldsDB($_data, $colNamehasAttachment);
				}

				Toastr::success("$this->type created successfully", "Create $this->type");
			}
			return redirect(route("{$type}_edit.edit", $data->id));
		} catch (\Exception $e) {
			dd($e->getMessage());
		};
	}

	public function update(Request $request, $id)
	{

		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
		$colNamehasAttachment = Helper::getColNamebyControls($props, 'attachment');
		$arrayExcept = array_merge(['_token', '_method', 'created_at', 'updated_at'], $colNamehasAttachment);
		$dataInput = $request->except($arrayExcept);


		$type = Str::plural($this->type);

		$this->deleteMediaIfNeeded($dataInput);

		$data = $this->data::find($id);

		$hasAttachment = $this->saveMediaValidator('update', $request, $dataInput, $data, $colNamehasAttachment);

		if ($hasAttachment) $this->updateIdsMediaToFieldsDB($data, $colNamehasAttachment);

		$this->_validate($props, $request);

		$newDataInput = $this->handleToggle('update', $props, $dataInput);

		$data->fill($newDataInput);
		$data->save();


		if ($data->save()) {
			$this->syncManyToManyRelationship($data, $dataInput);
		}

		if ($data->save()) Toastr::success("$this->type updated successfully", "Update $this->type");
		return redirect(route("{$type}_edit.edit", $id));
	}

	private function _validate($props, $request)
	{
		$itemValidations = [];
		foreach ($props as $value) {
			if (!is_null($value['validation'])) $itemValidations[$value['column_name']] = $value['validation'];
		}
		$request->validate($itemValidations);
	}

	private function handleToggle($method, $props, &$dataInput)
	{
		$switchControls = array_filter($props, fn ($prop) => $prop['control'] === 'switch');
		$switchControls = array_values(array_map(fn ($item) => $item['column_name'], $switchControls));
		// dd($switchControls);
		foreach ($switchControls as $switch) {
			$dataInput[$switch] = isset($dataInput[$switch]) ? ($method === 'store' ? $dataInput[$switch] : 1)  : null;
		}

		return $dataInput;
	}
	public function updateIdsMediaToFieldsDB($data, $colNamehasAttachment)
	{
		$dbMorphManyMedia = json_decode($data->media()->select('id', 'category')->get(), true);
		$media_cateTb = json_decode(DB::table('media_categories')->select('id', 'name')->get(), true);
		$ids_names_cateMedia = array_column($media_cateTb, 'name', 'id');
		$ids_names_cateTb = array_column($dbMorphManyMedia, 'category', 'id');

		$idsHasAttachMent = array_values(array_unique($ids_names_cateTb));
		$names_val_fileds = [];
		foreach ($ids_names_cateTb as $key => $value) {
			foreach ($idsHasAttachMent as $cate) {
				if ($value === $cate) {
					$names_val_fileds[$ids_names_cateMedia[$cate]][] = $key;
					break;
				}
			}
		}

		$valFileds = array_map(fn ($item) => $item = implode(",", $item), $names_val_fileds);
		foreach ($colNamehasAttachment as $attach) {
			if (!isset($valFileds[$attach])) {
				$valFileds[$attach] = null;
			}
		}
		$data->fill($valFileds);
		$data->save();
	}
}
