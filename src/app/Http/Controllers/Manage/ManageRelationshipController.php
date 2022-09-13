<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use function GuzzleHttp\json_decode;

abstract class ManageRelationshipController extends Controller
{
    protected $type = "";
    protected $typeModel = "";
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
        $typeModel = $this->typeModel;
        $model = App::make($typeModel);
        $columnEloquentParams = $model->eloquentParams;
        $dataManage = $this->path();
        if (!$dataManage) {
            $columnNames = array_keys($columnEloquentParams);
            return view('dashboards.props.managerelationship')->with(compact('type', 'columnNames', 'columnEloquentParams'));
        } else {
            $names = [];
            $columnNames = [];
            $columnEloquents = [];
            $columnParam1s = [];
            $columnParam2s = [];
            $columnParam3s = [];
            $columnParam4s = [];
            $columnParam5s = [];
            $columnParam6s = [];
            $columnLabels = [];
            $columnControls = [];
            $columnControlParams = [];
            $columnColSpans = [];
            $columnNewLines = [];
            $colorLines = [];
            $arrayCheck = [];
            foreach ($dataManage as $key => $data) {
                $names[$key] = $key;
                $columnNames[$key] = $data['column_name'];
                $columnEloquents[$key] = $data['eloquent'];
                $columnParam1s[$key] = $data['param_1'];
                $columnParam2s[$key] = $data['param_2'];
                $columnParam3s[$key] = $data['param_3'];
                $columnParam4s[$key] = $data['param_4'];
                $columnParam5s[$key] = $data['param_5'];
                $columnParam6s[$key] = $data['param_6'];
                array_push($arrayCheck, $data['eloquent'], $data['param_1'], $data['param_2'], $data['param_3'], $data['param_4'], $data['param_5'], $data['param_6']);
                $columnLabels[$key] = $data['label'];
                $columnControls[$key] = $data['control'];
                $columnControlParams[$key] = $data['control_param'];
                $columnColSpans[$key] = $data['col_span'];
                $columnNewLines[$key] = $data['new_line'];
                $colorLines[$key] = $data['type_line'];
            }
            $checkData = array_keys($model->eloquentParams);
            $diff1 = array_diff($columnNames, $checkData);
            $diff2 = array_diff($checkData, $columnNames);
            $diff3 = [];
            $dataCheck = $model->eloquentParams;
            foreach ($dataCheck as $key => $value) {
                $diffCheck = array_diff($value, $arrayCheck);
                if (count($diffCheck) > 0) {
                    array_push($diff3, $key);
                }
            }
            if (empty($diff1) && empty($diff2) && empty($diff3)) {
                return view('dashboards.props.managerelationship')->with(compact('type', 'names', 'columnNames', 'columnEloquents', 'columnParam1s', 'columnParam2s', 'columnParam3s', 'columnParam4s', 'columnParam5s', 'columnParam6s', 'columnLabels', 'columnControls', 'columnControlParams', 'columnColSpans', 'columnNewLines', 'colorLines'));
            } else {
                foreach ($diff2 as $value) {
                    $names['_' . $value] = '_' . $value;
                    $columnNames['_' . $value] = $value;
                    $columnLabels['_' . $value] = $value;
                    $columnEloquents['_' . $value] = $columnEloquentParams[$value][0] ?? null;
                    $columnParam1s['_' . $value] = $columnEloquentParams[$value][1] ?? null;
                    $columnParam2s['_' . $value] = $columnEloquentParams[$value][2] ?? null;
                    $columnParam3s['_' . $value] = $columnEloquentParams[$value][3] ?? null;
                    $columnParam4s['_' . $value] = $columnEloquentParams[$value][4] ?? null;
                    $columnParam5s['_' . $value] = $columnEloquentParams[$value][5] ?? null;
                    $columnParam6s['_' . $value] = $columnEloquentParams[$value][6] ?? null;
                    $columnControls['_' . $value] = "input";
                    $columnColSpans['_' . $value] = "12";
                    $columnNewLines['_' . $value] = "false";
                    $colorLines['_' . $value] = "new";
                }
                foreach ($diff1 as $value) {
                    $colorLines['_' . $value] = "removed";
                }
                foreach ($diff3 as $value) {
                    $columnEloquents['_' . $value] = $columnEloquentParams[$value][0] ?? null;
                    $columnParam1s['_' . $value] = $columnEloquentParams[$value][1] ?? null;
                    $columnParam2s['_' . $value] = $columnEloquentParams[$value][2] ?? null;
                    $columnParam3s['_' . $value] = $columnEloquentParams[$value][3] ?? null;
                    $columnParam4s['_' . $value] = $columnEloquentParams[$value][4] ?? null;
                    $columnParam5s['_' . $value] = $columnEloquentParams[$value][5] ?? null;
                    $columnParam6s['_' . $value] = $columnEloquentParams[$value][6] ?? null;
                    $colorLines['_' . $value] = "new";
                }
                return view('dashboards.props.managerelationship')->with(compact('type', 'names', 'columnNames', 'columnEloquents', 'columnParam1s', 'columnParam2s', 'columnParam3s', 'columnParam4s', 'columnParam5s', 'columnParam6s', 'columnLabels', 'columnControls', 'columnControlParams', 'columnColSpans', 'columnNewLines', 'colorLines'));
            }
        }
    }
    public function store(Request $request)
    {
        $data = $request->input();
        $magane = [];
        foreach ($data['name'] as $key => $name) {
            $array = [];
            $array['column_name'] = $data['column_name'][$key];
            $array['eloquent'] = $data['eloquent'][$key];
            $array['param_1'] = $data['param_1'][$key];
            $array['param_2'] = $data['param_2'][$key];
            $array['param_3'] = $data['param_3'][$key];
            $array['param_4'] = $data['param_4'][$key];
            $array['param_5'] = $data['param_5'][$key];
            $array['param_6'] = $data['param_6'][$key];
            $array['label'] = $data['label'][$key];
            $array['control'] = $data['control'][$key];
            $array['control_param'] = $data['control_param'][$key];
            $array['col_span'] = $data['col_span'][$key];
            $array['new_line'] = $data['new_line'][$key];
            $array['type_line'] = "default";
            $magane[$name] = $array;
        }
        $jsonManage = json_encode($magane);
        try {
            Storage::disk('json')->put("entities/{$this->type}/relationships.json", $jsonManage, 'public');
            Toastr::success('Save file json successfully', 'Save file json');
            return back();
        } catch (\Throwable $th) {
            Toastr::warning($th, 'Save file json');
        }
    }
    public function destroy($name)
    {
        $dataManage = $this->path();
        unset($dataManage[$name]);
        try {
            Storage::disk('json')->put("entities/{$this->type}/relationships.json", json_encode($dataManage), 'public');
            Toastr::success('Save file json successfully', 'Save file json');
            return response()->json(['message' => 'Successfully'], 200);
        } catch (\Throwable $th) {
            Toastr::warning('$th', 'Save file json');
            return response()->json(['message' => 'Failed delete'], 404);
        }
    }
    protected function path()
    {
        $path = storage_path() . "/json/entities/{$this->type}/relationships.json";
        if (file_exists($path)) {
            $dataManage = json_decode(file_get_contents($path), true);
            return $dataManage;
        } else {
            return false;
        }
    }
    protected function checkDataUpdate($columnEloquentParams)
    {
        $jsonColumnEloquentParams = json_encode($columnEloquentParams);
        Storage::disk('json')->put("entities/{$this->type}/eloquentParams.json", $jsonColumnEloquentParams, 'public');
    }
}
