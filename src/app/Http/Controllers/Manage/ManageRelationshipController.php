<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Services\Manage\ManageService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

abstract class ManageRelationshipController extends Controller
{
    protected $type = "";
    protected $typeModel = "";
    protected $manageService;
    public function __construct(ManageService $manageService)
    {
        $this->manageService = $manageService;
    }
    public function getType()
    {
        return $this->type;
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
        if (!isset($columnEloquentParams)) {
            $title = 'Warning Settings';
            $message = 'Eloquent Param is empty!';
            $type = 'warning';
            return view('components.feedback.result')->with(compact('title', 'message', 'type'));
        }
        $dataManage = $this->manageService->path($this->type, 'relationships');
        if (!$dataManage) {
            $columnRelationships = array_keys($columnEloquentParams);
            return view('dashboards.props.managerelationship')->with(compact('type', 'columnRelationships', 'columnEloquentParams'));
        } else {
            $names = [];
            $columnRelationships = [];
            $columnEloquents = [];
            $columnParam1s = [];
            $columnParam2s = [];
            $columnParam3s = [];
            $columnParam4s = [];
            $columnParam5s = [];
            $columnParam6s = [];
            $columnLabels = [];
            $columnRenderers = [];
            $columnRendererParams = [];
            $columnControlNames = [];
            $columnColSpans = [];
            $columnHidden = [];
            $columnNewLines = [];
            $colorLines = [];
            $arrayCheck = [];
            foreach ($dataManage as $key => $data) {
                $names[$key] = $key;
                $columnRelationships[$key] = $data['relationship'] ?? "";
                $columnEloquents[$key] = $data['eloquent'] ?? "";
                $columnParam1s[$key] = $data['param_1'] ?? "";
                $columnParam2s[$key] = $data['param_2'] ?? "";
                $columnParam3s[$key] = $data['param_3'] ?? "";
                $columnParam4s[$key] = $data['param_4'] ?? "";
                $columnParam5s[$key] = $data['param_5'] ?? "";
                $columnParam6s[$key] = $data['param_6'] ?? "";
                array_push($arrayCheck, $data['eloquent'], $data['param_1'], $data['param_2'], $data['param_3'], $data['param_4'], $data['param_5'], $data['param_6']);
                $columnLabels[$key] = $data['label'] ?? "";
                $columnRenderers[$key] = $data['renderer'] ?? "";
                $columnRendererParams[$key] = $data['renderer_param'] ?? "";
                $columnControlNames[$key] = $data['control_name'] ?? "";
                $columnColSpans[$key] = $data['col_span'] ?? "";
                $columnHidden[$key] = $data['hidden'] ?? "";
                $columnNewLines[$key] = $data['new_line'] ?? "";
                $colorLines[$key] = $data['type_line'] ?? "";
            }
            $checkData = array_keys($model->eloquentParams);
            $diff1 = array_diff($columnRelationships, $checkData);
            $diff2 = array_diff($checkData, $columnRelationships);
            $diff3 = [];
            $dataCheck = $model->eloquentParams;
            foreach ($dataCheck as $key => $value) {
                $diffCheck = array_diff($value, $arrayCheck);
                if (count($diffCheck) > 0) {
                    array_push($diff3, $key);
                }
            }
            if (empty($diff1) && empty($diff2) && empty($diff3)) {
                return view('dashboards.props.managerelationship')->with(compact('type', 'names', 'columnRelationships', 'columnControlNames', 'columnEloquents', 'columnParam1s', 'columnParam2s', 'columnParam3s', 'columnParam4s', 'columnParam5s', 'columnParam6s', 'columnLabels', 'columnRenderers', 'columnRendererParams', 'columnColSpans', 'columnHidden', 'columnNewLines', 'colorLines'));
            } else {
                foreach ($diff2 as $value) {
                    $names['_' . $value] = '_' . $value;
                    $columnRelationships['_' . $value] = $value;
                    $columnLabels['_' . $value] = $value;
                    $columnEloquents['_' . $value] = $columnEloquentParams[$value][0] ?? null;
                    $columnParam1s['_' . $value] = $columnEloquentParams[$value][1] ?? null;
                    $columnParam2s['_' . $value] = $columnEloquentParams[$value][2] ?? null;
                    $columnParam3s['_' . $value] = $columnEloquentParams[$value][3] ?? null;
                    $columnParam4s['_' . $value] = $columnEloquentParams[$value][4] ?? null;
                    $columnParam5s['_' . $value] = $columnEloquentParams[$value][5] ?? null;
                    $columnParam6s['_' . $value] = $columnEloquentParams[$value][6] ?? null;
                    $columnRenderers['_' . $value] = "";
                    $columnRendererParams['_' . $value] = "";
                    $columnControlNames['_' . $value] = "";
                    $columnColSpans['_' . $value] = "12";
                    $columnHidden['_' . $value] = "false";
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
                    $columnControlNames['_' . $value] = "";
                    $colorLines['_' . $value] = "new";
                }
                return view('dashboards.props.managerelationship')->with(compact('type', 'names', 'columnRelationships', 'columnControlNames', 'columnEloquents', 'columnParam1s', 'columnParam2s', 'columnParam3s', 'columnParam4s', 'columnParam5s', 'columnParam6s', 'columnLabels', 'columnRenderers', 'columnRendererParams', 'columnColSpans', 'columnHidden', 'columnNewLines', 'colorLines'));
            }
        }
    }
    public function store(Request $request)
    {
        $data = $request->input();
        $manage = [];
        if (isset($data['name'])) {
            foreach ($data['name'] as $key => $name) {
                $array = [];
                $array['relationship'] = $data['relationship'][$key];
                $array['eloquent'] = $data['eloquent'][$key];
                $array['param_1'] = $data['param_1'][$key];
                $array['param_2'] = $data['param_2'][$key];
                $array['param_3'] = $data['param_3'][$key];
                $array['param_4'] = $data['param_4'][$key];
                $array['param_5'] = $data['param_5'][$key];
                $array['param_6'] = $data['param_6'][$key];
                $array['label'] = $data['label'][$key];
                $array['renderer'] = $data['renderer'][$key];
                $array['renderer_param'] = $data['renderer_param'][$key];
                $array['control_name'] = $data['control_name'][$key];
                $array['col_span'] = $data['col_span'][$key];
                $array['hidden'] = $data['hidden'][$key];
                $array['new_line'] = $data['new_line'][$key];
                $array['type_line'] = "default";
                $manage[$name] = $array;
            }
        } else {
            $manage = (object)[];
        }
        try {
            $this->manageService->checkUploadFile($manage, $this->type, 'relationships');
            return back();
        } catch (\Throwable $th) {
            Toastr::warning($th, 'Save file json');
        }
    }
    public function destroy($name)
    {
        $res = $this->manageService->destroy($name, $this->type, 'relationships');
        if ($res) return response()->json(['message' => 'Successfully'], 200);
        return response()->json(['message' => 'Failed delete'], 404);
    }
}
