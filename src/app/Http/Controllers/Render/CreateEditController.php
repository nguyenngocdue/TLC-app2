<?php

namespace App\Http\Controllers\Render;

use App\Http\Controllers\Controller;
use App\Http\Services\ReadingFileService;
use App\Http\Services\UploadService;
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

	private function getProps()
	{
		$type = Str::plural($this->type);
		$path = storage_path("/json/entities/$type/props.json");
		// dd($path);
		$props = json_decode(file_get_contents($path), true);
		return $props;
	}


	public function show($id)
	{
		$currentUser = $this->data::find($id);
		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
		$type = Str::plural($this->type);
		$action = $this->action;
		$values = $action === "create" ? "" : $currentUser;

		$tablePath = $this->data;
		return view('dashboards.render.createEdit')->with(compact('props', 'values', 'type', 'action', 'currentUser', 'tablePath'));
	}

	public function update(Request $request, $id)
	{

		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
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

		// upload multiple pictures
		$idMediaArray = $this->upload->store($request, $id);
		$itemAttachment = [];
		foreach ($props as $key => $value) {
			if ($value['control'] === 'attachment') {
				array_push($itemAttachment, $value['column_name']);
			}
		}


		// Update dataInput to database
		foreach ($dataInput as $key => $value) {
			// dd($data->{$key});
			$data->{$key} = request($key);
		}

		// chanage value from toggle
		foreach ($props as $key => $value) {
			if ($value['control'] === 'switch') {
				$item = $value['column_name'];
				isset($dataInput[$item]) ? $data[$item] = 1 : $data[$item] = null;
			};
		}


		foreach ($itemAttachment as $key => $value) {
			if (count($idMediaArray) > 0 && isset($idMediaArray[$key])) {
				$data[$value] = $idMediaArray[$key];
			}
		}
		$data->save();
		$type = Str::plural($this->type);
		return redirect(route("{$type}_edit.show", $id));
	}
	public function index()
	{
		$action = $this->action;
		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);

		if ($props  === false) {
			$title = "Setting is missing";
			$error =  "File \"$this->r_fileName\" is missing. <br/>Please create this file by \"manage prop\".";
			return view('components.render.warning')->with(compact('error', 'title'));
		}


		$type = $this->type;
		$tablePath = $this->data;
		$values = "";

		return view('dashboards.render.createEdit')->with(compact('props', 'type', 'action', 'tablePath', 'values'));
	}
	public function store(Request $request)
	{
		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
		$dataInput = $request->input();
		unset($dataInput['_token']);
		unset($dataInput['_method']);
		$db = $this->data;


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

		// dd($dataInput);


		// Save data to database
		$array = [];
		foreach ($dataInput as $key => $value) {
			$array[$key] = $value;
		}
		$newData = $db::create($array);

		$idNewData = $newData->id;
		// Save picture
		$idMediaArray = $this->upload->store($request, $idNewData);
		// filter fields have "attachment control"
		$itemAttachment = [];
		foreach ($props as $key => $value) {
			if ($value['control'] === 'attachment') {
				array_push($itemAttachment, $value['column_name']);
			}
		}
		// push value of picture into database
		foreach ($itemAttachment as $key => $value) {
			if (count($idMediaArray) > 0 && isset($idMediaArray[$key])) {
				$newData[$value] = $idMediaArray[$key];
			}
		}

		// chanage value from toggle
		foreach ($props as $key => $value) {
			if ($value['control'] === 'toggle') {
				$item = $value['column_name'];
				isset($dataInput[$item]) ? $newData[$item] = 1 : $newData[$item] = 0;
			};
		}
		$type = Str::plural($this->type);
		return redirect(route("{$type}_edit.update", $idNewData));
	}
}
