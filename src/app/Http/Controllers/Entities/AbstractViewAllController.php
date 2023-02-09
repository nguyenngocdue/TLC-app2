<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntitySuperPropsFilter;
use App\Http\Controllers\UpdateUserSettings;
use App\Models\Field;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\JsonControls;
use App\Utils\Support\Json\Props;
use App\Utils\Support\Json\Relationships;
use App\Utils\Support\Json\SuperProps;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class AbstractViewAllController extends Controller
{
    use TraitEntitySuperPropsFilter;
    protected $type = "";
    protected $typeModel = '';
    protected $permissionMiddleware;

    public function __construct()
    {
        $this->middleware("permission:{$this->permissionMiddleware['read']}")->only('index');
        $this->middleware("permission:{$this->permissionMiddleware['edit']}")->only('index', 'update');
        $this->middleware("permission:{$this->permissionMiddleware['delete']}")->only('index', 'update', 'destroy');
    }

    public function getType()
    {
        return $this->type;
    }

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $pageLimit = $settings[$type]['page_limit'] ?? 20;
        $columnLimit = $settings[$type]['columns'] ?? null;
        $advanceFilter = $settings[$type]['advance_filters'] ?? null;
        return [$pageLimit, $columnLimit, $advanceFilter];
    }
    private function distributeFilter($advanceFilters, $propsFilters)
    {
        $propsFilters = array_map(fn ($item) => $item['control'], $propsFilters);
        if (!empty($advanceFilters)) {
            $advanceFilters = array_filter($advanceFilters, fn ($item) => $item);
            $result = [];
            foreach ($advanceFilters as $key => $value) {
                switch ($propsFilters['_' . $key]) {
                    case 'id':
                        $result['id'][$key] = $value;
                        break;
                    case 'text':
                    case 'textarea':
                    case 'number':
                    case 'status':
                        $result['text'][$key] = $value;
                        break;
                    case 'toggle':
                        $result['toggle'][$key] = $value;
                        break;
                    case 'dropdown':
                        $result['dropdown'][$key] = $value;
                        break;
                    case 'radio':
                    case 'checkbox':
                    case 'dropdown_multi':
                        $result['dropdown_multi'][$key] = $value;
                        break;
                    case 'picker_time':
                        $result['picker_time'][$key] = $value;
                        break;
                    case 'picker_datetime':
                    case 'picker_date':
                    case 'picker_month':
                    case 'picker_week':
                    case 'picker_quarter':
                    case 'picker_year':
                        $result['picker_datetime'][$key] = $value;
                        break;
                    default:
                        break;
                }
            }
            return $result;
        }
        return false;
    }

    private function getDataSource($pageLimit, $advanceFilters = null)
    {
        $propsFilters = $this->advanceFilter();
        $advanceFilters = $this->distributeFilter($advanceFilters, $propsFilters);
        $model = $this->typeModel;
        $search = request('search');
        $result = App::make($model)::search($search)
            ->query(function ($q) use ($advanceFilters, $propsFilters) {
                if ($advanceFilters) {
                    $queryResult = array_filter($advanceFilters, fn ($item) => $item);
                    array_walk($queryResult, function ($value, $key) use ($q, $propsFilters) {
                        switch ($key) {
                            case 'id':
                                array_walk($value, function ($value, $key) use ($q) {
                                    $arrayId = explode(',', $value);
                                    if (!empty($arrayId)) {
                                        $q->whereIn($key, $arrayId);
                                    }
                                });
                                break;
                            case 'text':
                                array_walk($value, function ($value, $key) use ($q) {
                                    $q->where($key, 'like', $value . '%');
                                });
                                break;
                            case 'toggle':
                                array_walk($value, function ($value, $key) use ($q) {
                                    $q->where($key, $value);
                                });
                                break;
                            case 'picker_time':
                                array_walk($value, function ($value, $key) use ($q) {
                                    $arrayTime = explode(' - ', $value);
                                    $q->whereTime($key, '>=', $arrayTime[0])
                                        ->whereTime($key, '<=', $arrayTime[1]);
                                });
                                break;
                            case 'picker_datetime':
                                array_walk($value, function ($value, $key) use ($q) {
                                    $arrayDate = explode(' - ', $value);
                                    $q->whereDate($key, '>=', $this->convertDateTime($arrayDate[0]))
                                        ->whereDate($key, '<=', $this->convertDateTime($arrayDate[1]));
                                });
                                break;
                            case 'dropdown':
                                array_walk($value, function ($value, $key) use ($q) {
                                    $q->whereIn($key, $value);
                                });
                                break;
                            case 'dropdown_multi':
                                array_walk($value, function ($value, $key) use ($q, $propsFilters) {
                                    $relationship = $propsFilters['_' . $key]['relationships'];
                                    $oracyParams = $relationship['oracyParams'];
                                    $field = $key;
                                    $fieldId = DB::table('fields')->where('name', str_replace('()', '', $field))->value('id');
                                    $collectionFilter = DB::table('many_to_many')->where('field_id', $fieldId)
                                        ->where('term_type', $oracyParams[1])
                                        ->where('doc_type', $this->typeModel)
                                        ->whereIn('term_id', $value)
                                        ->get();
                                    $valueFilter = $collectionFilter->map(function ($item) {
                                        return $item->doc_id;
                                    })->toArray();
                                    $q->whereIn('id', $valueFilter);
                                });
                                break;
                            default:
                                break;
                        }
                    });
                }
                return $q->orderBy('updated_at', 'desc');
            })->paginate($pageLimit);
        return $result;
    }

    private function convertDateTime($time)
    {
        return date('Y-m-d', strtotime(str_replace('/', '-', $time)));
    }
    private function getColumns($type, $columnLimit)
    {
        function createObject($prop, $type)
        {
            $output = [
                'title' => $prop['label'],
                'dataIndex' => $prop['column_name'],
            ];
            if (isset($prop['width']) && $prop['width']) $output['width'] = $prop['width'];

            switch ($prop['control']) {
                case 'id':
                    $output['renderer'] = 'id';
                    $output['align'] = 'center';
                    $output['type'] = $type;
                    break;
                case 'qr_code':
                    $output['renderer'] = 'qr-code';
                    $output['align'] = 'center';
                    $output['type'] = $type;
                    $output['width'] = 10;
                    break;
                case 'toggle':
                    $output['renderer'] = "toggle";
                    $output['align'] = "center";
                    break;
                case "number":
                    $output['align'] = "right";
                    break;
                case "picker_datetime":
                case "picker_date":
                case "picker_time":
                case "picker_month":
                case "picker_week":
                case "picker_quarter":
                case "picker_year":
                case "picker_datetime":
                    $output['renderer'] = "date-time";
                    $output['align'] = "center";
                    $output['rendererParam'] = $prop['control'];
                    break;
                case "text":
                case "textarea":
                    $output['renderer'] = "text";
                    break;
                case "status":
                    $output['renderer'] = "status";
                    $output['align'] = 'center';
                    break;
                case "thumbnail":
                    $output['renderer'] = "thumbnail";
                    break;
                default:
                    break;
            }

            return $output;
        }

        $props = Props::getAllOf($type);
        $props = array_filter($props, fn ($prop) => !$prop['hidden_view_all']);
        $props = array_filter($props, fn ($prop) => $prop['column_type'] !== 'static');
        if ($columnLimit) {
            $allows = array_keys($columnLimit);
            $props = array_filter($props, fn ($prop) => in_array($prop['name'], $allows));
        }
        $qrCodeColumn = [
            'label' => "QR Code",
            'column_name' => 'id',
            'control' => 'qr_code',
        ];
        array_splice($props, 1, 0, [$qrCodeColumn]);
        $result = array_values(array_map(fn ($prop) => createObject($prop, $type), $props));
        // Log::info($result);
        return $result;
    }

    private function attachRendererIntoColumn(&$columns)
    {
        // Log::info($columns);
        // Log::info($rawDataSource);
        $model = App::make($this->typeModel);
        $eloquentParams = $model->eloquentParams;
        $oracyParams = $model->oracyParams;
        // Log::info($eloquentParams);

        $json = Relationships::getAllOf($this->type);
        if (is_array($columns)) {
            foreach ($columns as &$column) {
                $dataIndex = $column['dataIndex'];
                if (!isset($eloquentParams[$dataIndex]) && !isset($oracyParams[$dataIndex])) continue; //<<Id, Name, Slug...

                if (isset($eloquentParams[$dataIndex])) {
                    $relationship = $eloquentParams[$dataIndex][0];
                    $allows = JsonControls::getViewAllEloquents();
                } elseif (isset($oracyParams[$dataIndex])) {
                    $relationship = $oracyParams[$dataIndex][0];
                    $allows = JsonControls::getViewAllOracies();
                }

                if (in_array($relationship, $allows)) {
                    // dd($json, $dataIndex, $json["_{$dataIndex}"]);
                    if (!isset($json["_{$dataIndex}"])) {
                        throw new Exception("Please create [$dataIndex] in Relationships View");
                    } else {
                        $relationshipJson = $json["_{$dataIndex}"];
                        // dump($relationshipJson);
                        $column['renderer'] = $relationshipJson['renderer_view_all'] ?? "";
                        $column['rendererParam'] = $relationshipJson['renderer_view_all_param'] ?? "";
                    }
                }
            }
        }
    }

    private function attachEloquentNameIntoColumn(&$columns)
    {
        $eloquentParams = App::make($this->typeModel)->eloquentParams;

        $eloquent = [];
        foreach ($eloquentParams as $key => $eloquentParam) {
            if (in_array($eloquentParam[0], ['belongsTo'])) {
                $eloquent[$eloquentParam[2]] = $key;
            }
        }
        // Log::info($eloquent);
        // Log::info($columns);

        $keys = array_keys($eloquent);
        if (is_array($columns)) {
            foreach ($columns as &$column) {
                if (in_array($column['dataIndex'], $keys)) {
                    $column['dataIndex'] = $eloquent[$column['dataIndex']];
                }
            }
        }
    }

    public function index(Request $request)
    {
        if (!$request->input('page') && !empty($request->input())) {
            (new UpdateUserSettings())($request);
            return redirect()->back();
        }
        [$pageLimit, $columnLimit, $advanceFilters] = $this->getUserSettings();
        // Log::info($columnLimit);
        $type = Str::plural($this->type);
        $columns = $this->getColumns($type, $columnLimit);
        $dataSource = $this->getDataSource($pageLimit, $advanceFilters);

        $this->attachEloquentNameIntoColumn($columns); //<< This must be before attachRendererIntoColumn
        $this->attachRendererIntoColumn($columns);
        $searchableArray = App::make($this->typeModel)->toSearchableArray();

        return view('dashboards.pages.entity-view-all', [
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'pageLimit' => $pageLimit,
            'valueAdvanceFilters' => $advanceFilters,
            'type' => $type,
            'columns' => $columns,
            'dataSource' => $dataSource,
            'searchTitle' => "Search by " . join(", ", array_keys($searchableArray)),
        ]);
    }

    public function destroy($id)
    {
        try {
            $model = $this->typeModel;
            $data = App::make($model)->find($id);
            $data->delete();
            return response()->json(['message' => 'Delete User Successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 404);
        }
    }
}
