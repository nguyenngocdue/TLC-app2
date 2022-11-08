<?php

namespace App\Http\Controllers\Render;

use App\Http\Controllers\Controller;
use App\Http\Services\ReadingFileService;
use App\Http\Services\UploadService;
use App\Models\Media;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


abstract class CreateEditController extends Controller
{
	protected $type;
	protected $data;
	protected $action;
	protected $branchName = 'entities';
	protected $disk = 'json';
	protected $r_fileName = 'props.json';
	public function __construct(protected UploadService $upload, protected ReadingFileService $readingFileService)
	{
	}

	public function create()
	{
		// session(['mediaUploaded' => []]);
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

		$relationshipFile = $this->readingFileService->type_getPath($this->disk,  $this->branchName, $currentElement->getTable(), "relationships.json");
		$idItems = [];
		if ($relationshipFile) {
			foreach ($relationshipFile as $key => $value) {
				if ($value["eloquent"] === "belongsToMany") {
					$fn = $value['relationship'];
					$idItems[$value['control_name']] = json_decode($currentElement->$fn->pluck('id'));
				}
			}
		}

		return view('dashboards.pages.createEdit')->with(compact('props', 'values', 'type', 'action', 'currentElement', 'tablePath', 'idItems'));
	}

	private function _validate($props, $request)
	{
		$itemsValidation = [];
		foreach ($props as $value) {
			is_null($value['validation']) ? "" : $itemsValidation[$value['column_name']] = $value['validation'];
		}
		$request->validate($itemsValidation);
	}

	private function handleToggle($method, $props, &$dataInput)
	{
		switch ($method) {
			case 'store':
				// filter controls which are switch control - set its value is null
				$switchControls = array_filter($props, fn ($prop) => $prop['control'] === 'switch');
				$switchControls = array_values(array_map(fn ($item) => $item['column_name'], $switchControls));
				// dd($switchControls);
				foreach ($switchControls as $switch) {
					$dataInput[$switch] = !isset($dataInput[$switch]) ? null : $dataInput[$switch];
				}
				break;
			case 'update':
				foreach ($props as $value) {
					if ($value['control'] === 'switch') {
						$item = $value['column_name'];
						isset($dataInput[$item]) ? $data[$item] = 1 : $data[$item] = null;
					};
				}
				break;
		}
	}

	private function syncManyToManyRelationship($data, $dataInput) //checkBox
	{
		$relationshipFile = $this->readingFileService->type_getPath($this->disk,  $this->branchName, $data->getTable(), "relationships.json");
		if ($relationshipFile) {
			foreach ($relationshipFile as $value) {
				if ($value["eloquent"] === "belongsToMany") {
					$colNamePivots = isset($dataInput[$value['control_name']]) ?  $dataInput[$value['control_name']] : [];
					$fn = $value['relationship'];
					$data->{$fn}()->sync($colNamePivots);
				}
			}
		}
	}



	public function update(Request $request, $id)
	{
		$dataInput = $request->except(['_token', '_method', 'created_at', 'updated_at']);

		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
		$type = Str::plural($this->type);

		$this->deleteMediaIfNeeded($dataInput);

		$data = $this->data::find($id);



		$itemsValidation = [];
		foreach ($props as $value) {
			is_null($value['validation']) ? "" : $itemsValidation[$value['column_name']] = $value['validation'];
		}

		$validator =  Validator::make($request->all(), $itemsValidation);
		if ($validator->fails()) {
			$keyMediaDel = $this->deleteMediaIfNeeded($dataInput);

			$mediaUploaded = session('mediaUploaded');
			$_mediaUploaded = $this->handleUpload($request) + $mediaUploaded; // save old value of media were uploaded
			foreach ($keyMediaDel as $key => $value) {
				unset($_mediaUploaded[$value]);
			}
			session(['mediaUploaded' => $_mediaUploaded]);

			$saveMediaFialUpload = $this->handleUpload($request);
			if (count($saveMediaFialUpload) > 0) {
				foreach ($saveMediaFialUpload as $key => $value) {
					$data->media()->save(Media::find($key));
				}
				session(['mediaUploaded' => []]);
			}
		}


		$this->_validate($props, $request);

		$this->handleToggle('update', $props, $dataInput);

		$data->fill($dataInput);
		$data->save();


		if ($data->save()) {
			$this->syncManyToManyRelationship($data, $dataInput);
			$mediaUploaded = session('mediaUploaded');
			if (count($mediaUploaded) > 0) {
				foreach ($mediaUploaded as $key => $value) {
					$data->media()->save(Media::find($key));
				}
				// reset session of mediaUploaded
				session(['mediaUploaded' => []]);
			}
			$controlsMedia = $this->handleUpload($request);
			if (count($controlsMedia) > 0) {
				foreach ($controlsMedia as $key => $value) {
					$data->media()->save(Media::find($key));
				}
				// reset session of mediaUploaded
				session(['mediaUploaded' => []]);
			}
		}

		if ($data->save()) Toastr::success("$this->type updated successfully", "Update $this->type");
		return redirect(route("{$type}_edit.edit", $id));
	}


	public function store(Request $request)
	{
		$dataInput = $request->except(['_token', '_method', 'created_at', 'updated_at']);
		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
		$type = Str::plural($this->type);

		$dataInputHasAttachment = array_filter($dataInput, fn ($item) => is_array($item));
		$dataInputNotAttachMent =  array_filter($dataInput, fn ($item) => !is_array($item));

		// $this->deleteMediaIfNeeded($dataInput);

		$itemsValidation = [];
		foreach ($props as $value) {
			is_null($value['validation']) ? "" : $itemsValidation[$value['column_name']] = $value['validation'];
		}



		$validator =  Validator::make($request->all(), $itemsValidation);
		if ($validator->fails()) {
			$keyMediaDel = $this->deleteMediaIfNeeded($dataInput);

			$mediaUploaded = session('mediaUploaded');
			$_mediaUploaded = $this->handleUpload($request) + $mediaUploaded; // save old value of media were uploaded
			foreach ($keyMediaDel as $key => $value) {
				unset($_mediaUploaded[$value]);
			}
			session(['mediaUploaded' => $_mediaUploaded]);
		} else {
			$mediaUploaded = session('mediaUploaded');
			$_mediaUploaded = $this->handleUpload($request) + $mediaUploaded; // save old value of media were uploaded
			session(['mediaUploaded' => $_mediaUploaded]);
		}





		$this->_validate($props, $request);
		$this->handleToggle('store', $props, $dataInput);



		$data = $this->data::create($dataInputNotAttachMent);

		if (isset($data)) {
			// Checkbox
			$this->syncManyToManyRelationship($data, $dataInputHasAttachment);
			Toastr::success("$this->type created successfully", "Create $this->type");

			$mediaUploaded = session('mediaUploaded');
			if (count($mediaUploaded) > 0) {
				foreach ($mediaUploaded as $key => $value) {
					$data->media()->save(Media::find($key));
				}
				// reset session of mediaUploaded
				session(['mediaUploaded' => []]);
			}
		}

		return redirect(route("{$type}_edit.edit", $data->id));
	}

	private function handleUpload($request)
	{
		if (count($request->files) > 0) {
			$controlsMedia = $this->upload->store($request);
			// dd($controlsMedia);
			if (!is_array($controlsMedia)) {
				$title = "Not find item";
				$message = $controlsMedia->getMessage();
				$type = "warning";
				return view('components.feedback.result')->with(compact('message', 'title', 'type'));
			}
			return $controlsMedia;
		} else {
			return session('mediaUploaded');
		}
	}

	private function deleteMediaIfNeeded($dataInput)
	{
		$cateAttachment = DB::table('media_categories')->select('id', 'name')->get();
		$keyMediaDel = [];
		foreach ($cateAttachment as $value) {
			if (isset($dataInput[$value->name . "_deleted"])) {
				$idsDelete = explode(',', $dataInput[$value->name . "_deleted"]);
				foreach ($idsDelete as $value) {
					$media = Media::find($value);
					is_null($media) ? "" : $media->delete();
					$keyMediaDel[] = $value;
				}
			}
		}
		return $keyMediaDel;
	}
}
