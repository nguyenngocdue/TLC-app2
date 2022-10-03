<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Services\Manage\ManageService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function GuzzleHttp\json_decode;

abstract class ManagePropController extends Controller
{
    protected $type = "";
    protected $manageService;
    public function __construct(ManageService $manageService)
    {
        $this->manageService = $manageService;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $type = $this->type;
        $dataManage = $this->manageService->path($type, 'props');
        if (!$dataManage) {
            $columnNames = Schema::getColumnListing(Str::plural($type));
            $columnTypes = [];
            foreach ($columnNames as $columnName) {
                $typeColumn = Schema::getColumnType(Str::plural($type), $columnName);
                array_push($columnTypes, $typeColumn);
            }
            return view('dashboards.props.manageprop')->with(compact('type', 'columnNames', 'columnTypes'));
        } else {
            $names = [];
            $columnNames = [];
            $columnTypes = [];
            $columnLabels = [];
            $columnControls = [];
            $columnColSpans = [];
            $columnHidden = [];
            $columnNewLines = [];
            $colorLines = [];
            foreach ($dataManage as $key => $data) {
                $names[$key] = $key;
                $columnNames[$key] = $data['column_name'];
                $columnTypes[$key] = $data['column_type'];
                $columnLabels[$key] = $data['label'];
                $columnControls[$key] = $data['control'];
                $columnColSpans[$key] = $data['col_span'];
                $columnHidden[$key] = $data['hidden'];
                $columnNewLines[$key] = $data['new_line'];
                $colorLines[$key] = $data['type_line'];
            }
            $diff1 = array_diff($columnNames, Schema::getColumnListing(Str::plural($type)));
            $diff2 = array_diff(Schema::getColumnListing(Str::plural($type)), $columnNames);
            if (empty($diff1) && empty($diff2)) {
                return view('dashboards.props.manageprop')->with(compact('type', 'names', 'columnNames', 'columnTypes', 'columnLabels', 'columnControls', 'columnColSpans', 'columnHidden', 'columnNewLines', 'colorLines'));
            } else {
                foreach ($diff2 as $value) {
                    $names['_' . $value] = '_' . $value;
                    $columnNames['_' . $value] = $value;
                    $typeColumn = Schema::getColumnType(Str::plural($type), $value);
                    $columnTypes['_' . $value] = $typeColumn;
                    $columnLabels['_' . $value] = $value;
                    $columnControls['_' . $value] = "input";
                    $columnColSpans['_' . $value] = "12";
                    $columnHidden['_' . $value] = "false";
                    $columnNewLines['_' . $value] = "false";
                    $colorLines['_' . $value] = "new";
                }
                foreach ($diff1 as $value) {
                    $colorLines['_' . $value] = "removed";
                }
                return view('dashboards.props.manageprop')->with(compact('type', 'names', 'columnNames', 'columnTypes', 'columnLabels', 'columnControls', 'columnColSpans', 'columnHidden', 'columnNewLines', 'colorLines'));
            }
        }
    }
    public function store(Request $request)
    {
        $data = $request->input();
        $manage = [];
        foreach ($data['name'] as $key => $name) {
            $array = [];
            $array['column_name'] = $data['column_name'][$key];
            $array['column_type'] = $data['column_type'][$key];
            $array['label'] = $data['label'][$key];
            $array['control'] = $data['control'][$key];
            $array['col_span'] = $data['col_span'][$key];
            $array['hidden'] = $data['hidden'][$key];
            $array['new_line'] = $data['new_line'][$key];
            $array['type_line'] = "default";
            $manage[$name] = $array;
        }
        try {
            $this->manageService->checkUploadFile($manage, $this->type, 'props');
            return back();
        } catch (\Throwable $th) {
            Toastr::warning($th, 'Save file json');
        }
    }
    public function destroy($name)
    {
        $res = $this->manageService->destroy($name, $this->type, 'props');
        if ($res) return response()->json(['message' => 'Successfully'], 200);
        return response()->json(['message' => 'Failed delete'], 404);
    }
}
