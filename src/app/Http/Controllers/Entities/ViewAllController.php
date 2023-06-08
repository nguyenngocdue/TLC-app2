<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityAdvancedFilter;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityDynamicType;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Http\Controllers\UpdateUserSettings;
use App\Http\Controllers\Workflow\LibApps;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\JsonControls;
use App\Utils\Support\Json\Relationships;
use App\Utils\Support\Json\SuperProps;
use App\Utils\System\Timer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ViewAllController extends Controller
{
    use TraitEntityDynamicType;
    use TraitEntityAdvancedFilter;
    use TraitViewAllFunctions;

    protected $type = "";
    protected $typeModel = '';
    protected $permissionMiddleware;

    private $frameworkTook = 0;

    public function __construct()
    {
        $this->frameworkTook = Timer::getTimeElapseFromLastAccess();
        $this->assignDynamicTypeViewAll();
        $this->middleware("permission:{$this->permissionMiddleware['read']}")->only('index');
    }

    public function getType()
    {
        return $this->type;
    }

    private function convertDateTime($time)
    {
        return date('Y-m-d', strtotime(str_replace('/', '-', $time)));
    }

    private function createObject($prop, $type)
    {
        $output = [
            'title' => $prop['label'],
            'dataIndex' => $prop['column_name'],
        ];
        $output['width'] = (isset($prop['width']) && $prop['width']) ? $prop['width'] : 100;

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
            case 'avatar':
                $output['renderer'] = 'avatar_user';
                $output['align'] = 'center';
                $output['type'] = $type;
                $output['width'] = 10;
                break;
            case 'action_column':
                $output['renderer'] = 'action_column';
                $output['align'] = 'center';
                $output['type'] = $type;
                $output['width'] = 10;
                break;
            case 'restore_column':
                $output['renderer'] = 'restore_column';
                $output['align'] = 'center';
                $output['type'] = $type;
                $output['width'] = 10;
                break;
            case 'checkbox_column':
                $output['renderer'] = 'checkbox_column';
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
            case "picker_time":
            case "picker_month":
            case "picker_date":
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
            case "hyperlink":
                $output['renderer'] = "hyper-link";
                $output['align'] = 'center';
                break;
            case "doc_id":
                $output['renderer'] = 'doc-id';
                $output['align'] = 'center';
                // $output['attributes']['id'] = 'id';
                $output['attributes']['type'] = 'type';
                break;
            default:
                break;
        }

        return $output;
    }

    private function getColumns($type, $columnLimit, $trash = false)
    {
        $props = SuperProps::getFor($type)['props'];
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
        if (app()->present()) {
            $trashInfoColumn = $trash ? [
                [
                    'label' => "Deleted By",
                    'column_name' => 'deleted_by',
                    'control' => 'avatar',
                ],
                [
                    'label' => "Deleted At",
                    'column_name' => 'deleted_at',
                    'control' => 'picker_datetime',
                    'width' => 170,
                ]
            ] : [];
            $actionColumn = $trash ? [
                'label' => "Action",
                'column_name' => 'id',
                'control' => 'restore_column',
            ] : [
                'label' => "Action",
                'column_name' => 'id',
                'control' => 'action_column',
            ];

            $checkboxColumn = [
                'label' => "",
                'column_name' => 'id',
                'control' => 'checkbox_column',
            ];
            array_splice($props, 0, 0, [$checkboxColumn, $actionColumn, ...$trashInfoColumn]);
        }
        $result = array_values(array_map(fn ($prop) => $this->createObject($prop, $type), $props));
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
                        $column['rendererUnit'] = $relationshipJson['renderer_view_all_unit'] ?? "";
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

    private function getTabPane($advanceFilters)
    {
        $currentStatus = isset($advanceFilters['status']) ? $advanceFilters['status'] : '';
        $dataTaxonomy = [];
        switch (JsonControls::getViewAllTabTaxonomy()) {
            case 'status':
                $dataTaxonomy = LibStatuses::getFor($this->type);
                break;
            default:
                break;
        }
        $tableName = Str::plural($this->type);
        $action = "updateValueAdvanceFilter";
        $dataSource = [
            'all' => [
                'href' => "?action=$action&_entity=" . $tableName . "&status%5B%5D=&",
                'title' => "<x-renderer.tag>All</x-renderer.tag>",
                'active' => true,
            ]
        ];
        if (!($this->typeModel)::isStatusless()) {
            foreach ($dataTaxonomy as $key => $value) {
                $isActive = ($currentStatus && count($currentStatus) == 1) && ($currentStatus[0] == $key);
                if ($isActive) $dataSource['all']['active'] = false;
                $dataSource[$key] = [
                    'href' => "?action=$action&_entity=" . $tableName . "&status%5B%5D=$key",
                    'title' => "<x-renderer.status>" . $key . "</x-renderer.status>",
                    'active' => $isActive,
                ];
            }
        }
        return $dataSource;
    }

    public function index(Request $request, $trashed = false)
    {
        $basicFilter = $request->input('basic_filter');
        if ($basicFilter || !empty($basicFilter)) {
            (new UpdateUserSettings())($request);
        }
        if (!$request->input('page') && !empty($request->input())) {
            (new UpdateUserSettings())($request);
            return redirect($request->getPathInfo());
        }
        [$perPage, $columnLimit, $advanceFilters, $currentFilter, $refreshPage] = $this->getUserSettings();
        // Log::info($columnLimit);
        $type = Str::plural($this->type);
        $columns = $this->getColumns($type, $columnLimit, $trashed);
        $dataSource = $this->getDataSource($advanceFilters, $trashed)->paginate($perPage);
        $this->attachEloquentNameIntoColumn($columns); //<< This must be before attachRendererIntoColumn
        $this->attachRendererIntoColumn($columns);
        // $searchableArray = App::make($this->typeModel)->toSearchableArray();
        $app = LibApps::getFor($this->type);
        $tableTrueWidth = !($app['hidden'] ?? false);
        if (app()->isProduction() || app()->isLocal()) $tableTrueWidth = false;
        return view('dashboards.pages.entity-view-all', [
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'title' => $trashed ? 'View Trash' : 'View All',
            'perPage' => $perPage,
            'valueAdvanceFilters' => $advanceFilters,
            'refreshPage' => $refreshPage,
            'type' => $type,
            'typeModel' => $this->typeModel,
            'columns' => $columns,
            'dataSource' => $dataSource,
            'currentFilter' => $currentFilter,
            // 'searchTitle' => "Search by " . join(", ", array_keys($searchableArray)),
            'tableTrueWidth' => $tableTrueWidth,
            'frameworkTook' => $this->frameworkTook,
            'trashed' => $trashed,
            'tabPane' => $this->getTabPane($advanceFilters),
        ]);
    }
    public function indexTrashed(Request $request)
    {
        return $this->index($request, true);
    }
}
