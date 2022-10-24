<?php

namespace App\Http\Controllers\Render;

use App\Http\Controllers\Controller;
use App\Http\Services\ReadingFileService;
use App\Http\Services\UploadService;
use App\Models\User;
use App\Models\Zunit_workplaces_rel_1;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


abstract class CreateEditController extends Controller
{

	protected $type;
	protected $data;
	protected $action;

	protected $upload;
	protected $branchName = 'entities';
	protected $disk = 'json';
	protected $r_fileName = 'props.json';
	protected $readingFileService;
	public function __construct(UploadService $upload, ReadingFileService $readingFileService)
	{
		$this->upload = $upload;
		$this->readingFileService = $readingFileService;
	}

	public function show($id)
	{
		$currentElement = $this->data::find($id);
		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
		$type = Str::plural($this->type);
		$action = $this->action;
		$values = $action === "create" ? "" : $currentElement;

		$tablePath = $this->data;

		$relation = $this->readingFileService->type_getPath($this->disk,  $this->branchName, 'zunit_test_1s', "relationships.json");

		$idItems = [];
		foreach ($relation as $key => $value) {
			if ($value["eloquent"] === "belongsToMany") {
				$fn = $value['relationship'];
				$idItems[$value['control_name']] = json_decode($currentElement->$fn->pluck('id'));
			}
		}
		return view('dashboards.render.createEdit')->with(compact('props', 'values', 'type', 'action', 'currentElement', 'tablePath', 'idItems'));
	}

	public function update(Request $request, $id)
	{
		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
		$type = Str::plural($this->type);
		$data = $this->data::find($id);
		$dataInput = $request->input();
		unset($dataInput['_token']);
		unset($dataInput['_method']);

		// Validation
		$itemsValidation = [];
		foreach ($props as $key => $value) {
			$itemsValidation[$value['column_name']] = $value['validation'];
		}
		$request->validate($itemsValidation);


		// chanage value from toggle
		foreach ($props as $key => $value) {
			if ($value['control'] === 'switch') {
				$item = $value['column_name'];
				isset($dataInput[$item]) ? $data[$item] = 1 : $data[$item] = null;
			};
		}




		// upload multiple pictures
		$idMediaArray = $this->upload->store($request, $id);
		$itemAttachment = [];
		foreach ($props as $key => $value) {
			if ($value['control'] === 'attachment') {
				array_push($itemAttachment, $value['column_name']);
			}
		}

		// Update dataInput to database
		$data->fill($dataInput);
		$data->save();
		if ($data->save()) Toastr::success('User updated successfully', 'Update User');



		// multisection - checkbox
		$relationshipFile = $this->readingFileService->type_getPath($this->disk,  $this->branchName, $data->getTable(), "relationships.json");
		foreach ($relationshipFile as $key => $value) {
			if ($value["eloquent"] === "belongsToMany") {
				$colNamePivot = isset($dataInput[$value['control_name']]) ?  $dataInput[$value['control_name']] : [];
				$fn = $value['relationship'];
				$data->{$fn}()->sync($colNamePivot);
			}
		}




		foreach ($itemAttachment as $key => $value) {
			if (count($idMediaArray) > 0 && isset($idMediaArray[$key])) {
				$data[$value] = $idMediaArray[$key];
			}
		}
		return redirect(route("{$type}_edit.show", $id));
	}
	public function index()
	{
		$action = $this->action;
		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);

		if ($props  === false) {
			$title = "Setting is missing";
			$error =  "File \"$this->r_fileName\" is missing. <br/>Please create this file by \"manage prop\".";
			return view('components.render.resultWarning')->with(compact('error', 'title'));
		}

		$type = $this->type;
		$tablePath = $this->data;
		$values = "";
		$idItems = [];

		return view('dashboards.render.createEdit')->with(compact('props', 'type', 'action', 'tablePath', 'values', 'idItems'));
	}
	public function store(Request $request)
	{

		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
		$type = Str::plural($this->type);
		$db = $this->data;
		$dataInput = $request->input();



		// Save picture
		$idMediaArray = $this->upload->store($request, 0);
		// filter fields have "attachment control"
		// $itemAttachment = [];
		// foreach ($props as $key => $value) {
		// 	if ($value['control'] === 'attachment') {
		// 		array_push($itemAttachment, $value['column_name']);
		// 	}
		// }


		// // push value of picture into database
		// foreach ($itemAttachment as $key => $value) {
		// 	if (count($idMediaArray) > 0 && isset($idMediaArray[$key])) {
		// 		$newData[$value] = $idMediaArray[$key];
		// 	}
		// }


		unset($dataInput['_token']);
		unset($dataInput['_method']);


		$itemsValidation = [];
		foreach ($props as $key => $value) {
			$itemsValidation[$value['column_name']] = $value['validation'];
		}
		$request->validate($itemsValidation);

		// filter controls which are switch control - set its value is null
		$witchControls = [];
		foreach ($props as $key => $value) {
			if ($value['control'] === "switch") {
				array_push($witchControls, $value['column_name']);
			}
		}
		foreach ($witchControls as $value) {
			if (isset($dataInput[$value]) === false) {
				$dataInput[$value] = null;
			}
		}

		// chanage value from toggle
		foreach ($props as $key => $value) {
			if ($value['control'] === 'toggle') {
				$item = $value['column_name'];
				isset($dataInput[$item]) ? $newData[$item] = 1 : $newData[$item] = null;
			};
		}
		// Save data to database
		$newData = $db::create($dataInput);

		// multiselection - checkbox
		$relationshipFile = $this->readingFileService->type_getPath($this->disk,  $this->branchName, $newData->getTable(), "relationships.json");
		foreach ($relationshipFile as $key => $value) {
			if ($value["eloquent"] === "belongsToMany") {
				$colNamePivot = isset($dataInput[$value['control_name']]) ? $dataInput[$value['control_name']] : [];
				$fn = $value['relationship'];
				$newData->{$fn}()->sync($colNamePivot);
			}
		}


		if ($newData) {
			Toastr::success('User created successfully', 'Create User');
		} else {
			return redirect()->back();
		}
		$idNewData = $newData->id;


		$type = Str::plural($this->type);
		return redirect(route("{$type}_edit.update", $idNewData));
	}
}
