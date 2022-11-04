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

		// dd($idItems);
		return view('dashboards.pages.createEdit')->with(compact('props', 'values', 'type', 'action', 'currentElement', 'tablePath', 'idItems'));
	}

	private function _validate($props, $request)
	{
		// Validation
		$itemsValidation = [];
		foreach ($props as $value) {
			$itemsValidation[$value['column_name']] = is_null($value['validation']) ? "" : $value['validation'];
		}
		$request->validate($itemsValidation);
	}

	private function deleteMediaIfNeeded($dataInput)
	{
		$cateAttachment = DB::table('media_categories')->select('id', 'name')->get();
		foreach ($cateAttachment as $value) {
			if (isset($dataInput[$value->name . "_deleted"])) {
				$idsDelete = explode(',', $dataInput[$value->name . "_deleted"]);
				foreach ($idsDelete as $value) {
					$media = Media::find($value);
					is_null($media) ? "" : $media->delete();
				}
			}
		}
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

	private function syncManyToManyRelationship($data, $dataInput)
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

	private function handleUpload($request, $data)
	{
		if (count($request->files) > 0) {
			$controlsMedia = $this->upload->store($request, $data->id);
			if (!is_array($controlsMedia)) {
				$title = "Not find item";
				$message = $controlsMedia->getMessage();
				$type = "warning";
				return view('components.feedback.result')->with(compact('message', 'title', 'type'));
			}
			if (count($controlsMedia) > 0) {
				foreach ($controlsMedia as $key => $value) {
					$data->media()->save(Media::find($key));
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
		$this->_validate($props, $request);
		$this->handleToggle('update', $props, $dataInput);

		$data = $this->data::find($id);
		$data->fill($dataInput);
		$data->save();

		$this->syncManyToManyRelationship($data, $dataInput);
		$this->handleUpload($request, $data);

		if ($data->save()) Toastr::success("$this->type updated successfully", "Update $this->type");
		return redirect(route("{$type}_edit.edit", $id));
	}

	public function store(Request $request)
	{
		$dataInput = $request->except(['_token', '_method', 'created_at', 'updated_at']);
		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
		$type = Str::plural($this->type);

		// $this->deleteMediaIfNeeded($dataInput);
		$this->_validate($props, $request);
		$this->handleToggle('store', $props, $dataInput);

		$data = $this->data::create($dataInput);

		if (isset($data)) {
			$this->syncManyToManyRelationship($data, $dataInput);
			$this->handleUpload($request, $data);
			Toastr::success("$this->type created successfully", "Create $this->type");
		}
		return redirect(route("{$type}_edit.edit", $data->id));
	}
}
