<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

abstract class ManageTablePropController extends Controller
{
    protected $type = "";

    public function __construct()
    {
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $type = $this->type;
        [$dataManage, $tableNames] = $this->path();
        if (!$dataManage) {
            $columnNames = [];
            $columnTypes = [];
            $columnTableNames = [];
            foreach ($tableNames as $key => $tableName) {
                $columnNameTable = Schema::getColumnListing($tableName);
                foreach ($columnNameTable as $columnName) {
                    $typeColumn = Schema::getColumnType($tableName, $columnName);
                    array_push($columnTypes, $typeColumn);
                    array_push($columnTableNames, $key);
                }
                $columnNames = array_merge($columnNames, $columnNameTable);
            }
            return view('dashboards.props.managelineprop')->with(compact('type', 'columnTableNames', 'columnNames', 'columnTypes'));
        } else {
            $columnTableNames = [];
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
                $columnTableNames[$key] = $data['table_name'];
                $columnNames[$key] = $data['column_name'];
                $columnTypes[$key] = $data['column_type'];
                $columnLabels[$key] = $data['label'];
                $columnControls[$key] = $data['control'];
                $columnColSpans[$key] = $data['col_span'];
                $columnHidden[$key] = $data['hidden'];
                $columnNewLines[$key] = $data['new_line'];
                $colorLines[$key] = $data['type_line'];
            }
            $globalColumnNames = [];
            foreach ($tableNames as $key => $tableName) {
                $columnNameTable = Schema::getColumnListing($tableName);
                foreach ($columnNameTable as $columnName) {
                    $globalColumnNames[$key . '|' . $columnName] = $columnName;
                }
            }
            $diff1 = array_diff_key($columnNames, $globalColumnNames);
            $diff2 = array_diff_key($globalColumnNames, $columnNames);
            if (empty($diff1) && empty($diff2)) {
                return view('dashboards.props.managelineprop')->with(compact('type', 'columnTableNames', 'names', 'columnNames', 'columnTypes', 'columnLabels', 'columnControls', 'columnColSpans', 'columnHidden', 'columnNewLines', 'colorLines'));
            } else {
                foreach ($diff2 as $key => $value) {
                    $keyTableName = explode('|', $key);
                    $names[$key] = $keyTableName[0] . '|' . $value;
                    $columnTableNames[$key] = $keyTableName[0];
                    $columnNames[$key] = $value;
                    $typeColumn = Schema::getColumnType($tableNames[$keyTableName[0]], $value);
                    $columnTypes[$key] = $typeColumn;
                    $columnLabels[$key] = $value;
                    $columnControls[$key] = "input";
                    $columnColSpans[$key] = "12";
                    $columnHidden[$key] = "false";
                    $columnNewLines[$key] = "false";
                    $colorLines[$key] = "new";
                }
                foreach ($diff1 as $key => $value) {
                    $colorLines[$key] = "removed";
                }
                return view('dashboards.props.managelineprop')->with(compact('type', 'columnTableNames', 'names', 'columnNames', 'columnTypes', 'columnLabels', 'columnControls', 'columnColSpans', 'columnHidden', 'columnNewLines', 'colorLines'));
            }
        }
    }
    public function store(Request $request)
    {
        $data = $request->input();
        $magane = [];
        foreach ($data['name'] as $key => $name) {
            $array = [];
            $array['table_name'] = $data['table_name'][$key];
            $array['column_name'] = $data['column_name'][$key];
            $array['column_type'] = $data['column_type'][$key];
            $array['label'] = $data['label'][$key];
            $array['control'] = $data['control'][$key];
            $array['col_span'] = $data['col_span'][$key];
            $array['hidden'] = $data['hidden'][$key];
            $array['new_line'] = $data['new_line'][$key];
            $array['type_line'] = "default";
            $magane[$name] = $array;
        }
        $jsonManage = json_encode($magane);
        try {
            $output = Storage::disk('json')->put("entities/{$this->type}/tables.json", $jsonManage, 'public');
            if ($output) {
                Toastr::success('Save file json successfully!', 'Save file json');
            } else {
                Toastr::warning('Maybe Permission is missing!', 'Save file json failed');
            }
            return back();
        } catch (\Throwable $th) {
            Toastr::warning($th, 'Save file json');
        }
    }
    public function destroy($name)
    {
        [$dataManage] = $this->path();
        unset($dataManage[$name]);
        try {
            $output = Storage::disk('json')->put("entities/{$this->type}/tables.json", json_encode($dataManage), 'public');
            if ($output) {
                Toastr::success('Save file json successfully!', 'Save file json');
            } else {
                Toastr::warning('Maybe Permission is missing!', 'Save file json failed');
            }
            return response()->json(['message' => 'Successfully'], 200);
        } catch (\Throwable $th) {
            Toastr::warning('$th', 'Save file json');
            return response()->json(['message' => 'Failed delete'], 404);
        }
    }
    protected function path()
    {
        $path = storage_path() . "/json/entities/{$this->type}/tables.json";
        $pathTableName = storage_path() . "/json/configs/table/{$this->type}/tableName.json";
        $tableNames = json_decode(file_get_contents($pathTableName), true);
        if (file_exists($path)) {
            $dataManage = json_decode(file_get_contents($path), true);
            return [$dataManage, $tableNames];
        } else {
            return [false, $tableNames];
        }
    }
}
