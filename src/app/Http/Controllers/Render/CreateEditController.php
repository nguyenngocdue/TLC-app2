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

	public function update(Request $request, $id)
	{
		$dataInput = $request->except(['_token', '_method', 'created_at', 'updated_at']);
		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
		$type = Str::plural($this->type);

		$cateAttachment = DB::table('media_categories')->select('id', 'name')->get();
		foreach ($cateAttachment as $key => $value) {
			if (isset($dataInput[$value->name . "_deleted"])) {
				$idsDelete =  explode(',', $dataInput[$value->name . "_deleted"]);
				foreach ($idsDelete as $value) {
					$media = Media::find($value);
					is_null($media) ? "" : $media->delete();
				}
			}
		}

		// Validation
		$itemsValidation = [];
		foreach ($props as $key => $value) {
			$itemsValidation[$value['column_name']] = is_null($value['validation']) ? "" : $value['validation'];
		}
		$request->validate($itemsValidation);

		$data = $this->data::find($id);
		// chanage value from toggle
		foreach ($props as $key => $value) {
			if ($value['control'] === 'switch') {
				$item = $value['column_name'];
				isset($dataInput[$item]) ? $data[$item] = 1 : $data[$item] = null;
			};
		}

		// Save data to database
		$data->fill($dataInput);
		$data->save();
		if ($data->save()) Toastr::success("$this->type updated successfully", "Update $this->type");

		// multisection - checkbox
		$relationshipFile = $this->readingFileService->type_getPath($this->disk,  $this->branchName, $data->getTable(), "relationships.json");
		if ($relationshipFile) {
			foreach ($relationshipFile as $key => $value) {
				if ($value["eloquent"] === "belongsToMany") {
					$colNamePivot = isset($dataInput[$value['control_name']]) ?  $dataInput[$value['control_name']] : [];
					$fn = $value['relationship'];
					$data->{$fn}()->sync($colNamePivot);
				}
			}
		}

		// Save pictures to Media of database
		if (count($request->files) > 0) {
			$controlsMedia = $this->upload->store($request, $id);
			if (!is_array($controlsMedia)) {
				$error = $controlsMedia->getMessage();
				Toastr::error("$this->type updated failed", $error);
			}
			foreach ($controlsMedia as $key => $value) {
				$data->media()->save(Media::find($key));
			}
		}

		return redirect(route("{$type}_edit.edit", $id));
	}

	public function store(Request $request)
	{
		$dataInput = $request->except(['_token', '_method', 'created_at', 'updated_at']);
		$props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
		$type = Str::plural($this->type);

		// Validation
		$itemsValidation = [];
		foreach ($props as $key => $value) {
			$itemsValidation[$value['column_name']] = is_null($value['validation']) ? "" : $value['validation'];
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
		$data = $this->data;
		$newData = $data::create($dataInput);

		if (isset($newData)) {
			$relationshipFile = $this->readingFileService->type_getPath($this->disk,  $this->branchName, $newData->getTable(), "relationships.json");
			// multiselection - checkbox
			if ($relationshipFile) {
				foreach ($relationshipFile as $key => $value) {
					if ($value["eloquent"] === "belongsToMany") {
						$colNamePivot = isset($dataInput[$value['control_name']]) ? $dataInput[$value['control_name']] : [];
						$fn = $value['relationship'];
						$newData->{$fn}()->sync($colNamePivot);
					}
				}
			}
			// Save pictures to Media of database
			if (count($request->files) > 0) {
				$controlsMedia = $this->upload->store($request, $newData->id);
				if (!is_array($controlsMedia)) {
					$title = "Not find item";
					$message = $controlsMedia->getMessage();
					$type = "warning";
					return view('components.feedback.result')->with(compact('message', 'title', 'type'));
				}
				if (count($controlsMedia) > 0) {
					foreach ($controlsMedia as $key => $value) {
						$newData->media()->save(Media::find($key));
					}
				}
			}
		}

		if ($newData) {
			Toastr::success("$this->type created successfully", "Create $this->type");
		} else {
			return redirect()->back();
		}
		$idNewData = $newData->id;

		$type = Str::plural($this->type);
		return redirect(route("{$type}_edit.edit", $idNewData));
	}
}
