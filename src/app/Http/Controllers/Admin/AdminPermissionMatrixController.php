<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\TraitViewAllMatrixCommon;
use App\Http\Controllers\Workflow\LibApps;
use App\Models\Role;
use App\Models\Role_set;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Entities;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class AdminPermissionMatrixController extends Controller
{
    use TraitViewAllMatrixCommon;
    private $nameColumnFixed = 'left';
    private $rotate45Width = false;
    private $groupBy = 'name_for_group_by';
    private $groupByLength = 3;
    private $type = 'permission_matrixes';
    private $tableTrueWidth = false;

    static $singletonDbRoleCollection = null;
    static $singletonDbRoleGetAllPermissionCollection = null;
    static $singletonDbRoleSetGetAllRoleCollection = null;
    public static function getRole(){
        return Role::all();
    }
    public static function getCollection()
    {
        if (!isset(static::$singletonDbRoleCollection)) {
            $all = static::getRole();
            foreach ($all as $item) $indexed[$item->id] = $item;
            static::$singletonDbRoleCollection = collect($indexed);
        }
        return static::$singletonDbRoleCollection;
    }
    public static function getCollectionRoleGetAllPermission()
    {
        if (!isset(static::$singletonDbRoleGetAllPermissionCollection)) {
            $all = static::getRole();
            foreach ($all as $item) $indexed[$item->id] = $item->permissions;
            static::$singletonDbRoleGetAllPermissionCollection = collect($indexed);
        }
        return static::$singletonDbRoleGetAllPermissionCollection;
    }

    public static function getCollectionRoleSetGetAllRole()
    {
        if (!isset(static::$singletonDbRoleSetGetAllRoleCollection)) {
            $all = Role_set::all();
            foreach ($all as $item) $indexed[$item->id] = $item->roles;
            static::$singletonDbRoleSetGetAllRoleCollection = collect($indexed);
        }
        return static::$singletonDbRoleSetGetAllRoleCollection;
    }

    public static function findFromCache($id)
    {
        return static::getCollection()[$id] ?? null;
    }
    public static function findPermissionByRoleIdFromCache($id){
        return static::getCollectionRoleGetAllPermission()[$id] ?? null;
    }
    public static function findRoleByRoleSetIdFromCache($id){
        return static::getCollectionRoleSetGetAllRole()[$id] ?? null;
    }

    public function getType()
    {
        return "permission_matrixes";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        [$xAxis,$xExtraColumns] = $this->getXAxis($this->getMatrixDataSource());
        $xAxis2ndHeading = $this->getXAxis2ndHeader($xAxis);
        $yAxis = $this->getYAxis();
        $columns = $this->getColumns($xAxis);
        $yAxisTableName = $this->getTableNameYAxis();
        $dataSource = $this->mergeDataSource($xAxis, $yAxis,$xExtraColumns);
        $settings = CurrentUser::getSettings();
        $per_page = $settings[$this->type]['view_all']['per_page'] ?? 10;
        $page = $settings[$this->type]['view_all']['matrix']['page'] ?? 1;
        $dataSource = $this->paginate($dataSource, $per_page, $page);
        $route = route('updateUserSettings');
        $perPage = "<x-form.per-page type='$this->type' route='$route' perPage='$per_page'/>";
        if ($r = $this->updateUserSettings($request)) return $r;
        return view(
            'admin.permission-matrix',
            [
                'topTitle' => Str::headline($this->getType()),
                'columns' => $columns,
                'dataSource' => $dataSource,
                'dataHeader' => $xAxis2ndHeading,
                'footer' => $this->getFooter($yAxisTableName),
                'perPage' => $perPage,
                'rotate45Width' => $this->rotate45Width,
                'groupBy' => $this->groupBy,
                'groupByLength' => $this->groupByLength,
                'tableTrueWidth' => $this->tableTrueWidth,
                // 'actionButtons' => $actionButtons,
                'headerTop' => 20,
                // 'tableTopCenterControl' => $this->tableTopCenterControl,
                // 'route' => $this->getRouteAfterSubmit(),
            ],
        );
    }
    protected function getFooter($yAxisTableName)
    {
        $yAxisRoute = route($yAxisTableName . ".index");
        $app = LibApps::getFor($yAxisTableName);
        return "<div class='flex items-center justify-start'>
                <span class='mr-1'>View all </span>
                <a target='_blank' class='text-blue-400 cursor-pointer font-semibold' href='$yAxisRoute'> " . $app['title'] . "</a>
            </div>";
    }
    public function getMatrixDataSource()
    {
        return  self::getCollection();
    }
    public function getItemDataSourceById($id = null){
        return Role::findFromCache($id);
    }
    protected function getColumns($extraColumns)
    {
        return  [
            ['dataIndex' => 'name_for_group_by', 'hidden' => true],
            ['dataIndex' => 'name', 'width' => 300, 'fixed' => $this->nameColumnFixed,],
            ['dataIndex' => 'role', 'width' => 50, 'fixed' => $this->nameColumnFixed,],
            ...$this->getMetaColumns(),
            ...$extraColumns,
            ...$this->getRightMetaColumns(),
        ];
    }
    private function getRightMetaColumns(){
        return [];
    }
    protected function getMetaColumns()
    {
        return [];
    }
    private function mergeDataSource($xAxis, $yAxis,$xExtraColumns)
    {
        $result = [];
        foreach ($yAxis as $y) {
            $yId = $y->id;
            $line = [];
            $line['name_for_group_by'] = $y->name;
            $line['name'] = (object)[
                'value' => $y->name,
                'cell_title' => "(#" . $yId . ")",
                'cell_class' => "text-blue-800 bg-white",
            ];
            $line['role'] = (object)[
                'value' => sizeof(self::findRoleByRoleSetIdFromCache($yId)) ?? 0,
                'cell_title' => "(#" . $yId . ")",
                'cell_class' => "text-blue-800 bg-white",
            ];
            foreach ($xAxis as $x) {
                $xId = $x['dataIndex'];
                if(isset($x['mapIndex'])){
                    $mapIndex = $x['mapIndex'];
                    $extraColumns = $xExtraColumns[$mapIndex] ?? [];
                    foreach ($extraColumns as $key => $column) {
                        $columnIndexName = $this->getPreviousNameRole($mapIndex,$key);
                        $key = $xId . "_" . $columnIndexName;
                        if(str_contains($key,'READ-DATA')) $key = $xId;
                        $cell = $y->roles->where('id',$column)->first();
                        $value = $this->makeCheckbox($cell,$y,$column,$columnIndexName,$mapIndex);
                        //<< convert to empty string for excel
                        $line[$key] = is_null($value) ? "" : $value;
                        }
                }
            }
            $result[] = $line;
        }
        return $result;
    }
    protected function getCheckboxVisible()
    {
        return true;
    }
    private function handleCheckbox($document,$y){
        if(!isset($document->id)) return '';
        return $y->hasRoleTo($document->id) ? 'checked' : '';
    }
    protected function makeCheckbox($document, $y,$id,$columnIndexName,$mapIndex)
    {
        $roleAllowedPermission = $this->getRoleAllowedPermission($columnIndexName);
        $permissions = self::findPermissionByRoleIdFromCache($id)->pluck('name')->toArray();
        $permissionsStr = join(",", $permissions);
        $arrayCheckAllowed = array_map(function($item) use ($mapIndex){
            return str_replace('-'.$mapIndex,'',$item);
        },$permissions);
        $bgColor = "";
        $textRender = "";
        $missingInArr = array_diff($arrayCheckAllowed,$roleAllowedPermission);
        $redundancyInArr = array_diff($roleAllowedPermission,$arrayCheckAllowed);
        [$bgColor,$textRender,$textPermission] = $this->getBackgroundColorAndTextRender($missingInArr,$redundancyInArr);
        $isCheckboxVisible = $this->getCheckboxVisible() ? 1 : 0;
        $isChecked = $this->handleCheckbox($document,$y);
        $className = $isCheckboxVisible ? "cursor-pointer view-all-matrix-checkbox-$id" : "cursor-not-allowed disabled:opacity-50";
        $disabledStr = $isCheckboxVisible ? "" : "disabled";
        $strMakeId = Str::makeId($id);
        $checkbox = "<input $disabledStr class='$className' title='$strMakeId | $permissionsStr' type='checkbox' $isChecked id='checkbox_$id' name='$id'/>";
        $item = [
            'value' => $checkbox . "<br/>" . $this->makeCaptionForCheckbox($textPermission,$textRender),
            'cell_class' => "$bgColor",
        ];
        return (object) $item;
    }
    private function getBackgroundColorAndTextRender($missingInArr,$redundancyInArr){
        $bgColor = "";
        $textRender = "...";
        $textPermission = "";
        if(!empty($missingInArr)){
            $bgColor = 'bg-red-200';
            $textRender = '-';
            $textPermission = join(",",$missingInArr);
        }
        if(!empty($redundancyInArr)){
            if($bgColor && $textRender) return ['bg-yellow-200','-+',join(',',array_merge($missingInArr,$redundancyInArr))];
            $bgColor = 'bg-pink-200';
            $textRender = '+';
            $textPermission = join(",",$redundancyInArr);
        }
        return [$bgColor,$textRender,$textPermission];
    }
    private function makeCaptionForCheckbox($textPermission,$textRender)
    {
        return "<div title='$textPermission'>$textRender</div>";
    }
    private function getXAxisPrimaryColumns()
    {
        return $this->getEntities();
    }
    private function getPreviousNameRole($ignoreName,$extraColumnName){
        return str_replace('-'.Str::upper($ignoreName),'',$extraColumnName);
    }
    private function getXAxis($dataSourceMatrix)
    {
        $result = [];
        $result2 = [];
        $data = $this->getXAxisPrimaryColumns();
        foreach ($data as $key => $value) {
            $extraColumns = $dataSourceMatrix->filter(function ($item) use ($value) {
                $arrayExplode = explode('-',$item->name);
                $check = end($arrayExplode);
                return $check === Str::upper($value);
            })->pluck('id','name')->toArray();
            $result2[$value] =  $extraColumns;
            foreach ($extraColumns as $extraColumnName => $extraColumnId) {
                $columnIndex = $this->getPreviousNameRole($value,$extraColumnName);
                if($columnIndex == 'READ-DATA'){
                    $result[] = [
                        'dataIndex' => $key,
                        'dataIndexMap' => $extraColumnId,
                        'columnIndex' => Str::lower($columnIndex),
                        'title' => Str::upper($value),
                        'mapIndex' => $value,
                        'align' => 'center',
                        'width' => 40,
                        "colspan" => sizeof($extraColumns),
                    ];
                }else{
                    $result[] = [
                        'dataIndex' => $key . "_" . $columnIndex,
                        'columnIndex' => Str::lower($columnIndex),
                        'align' => 'center',
                        'width' => 40,
                    ];
                }
            }
        }
        return [$result,$result2];
    }
    private function getRoleAllowedPermission($roleGroup){
        switch ($roleGroup) {
            case 'READ-DATA':
                return ['read'];
            case 'READ-WRITE-DATA':
                return ['read','create','edit','edit-others'];
            case 'ADMIN-DATA':
                return ['read','create','edit','edit-others','delete','delete-others'];
            default:
            return [];
        }
    }
    private function getXAxis2ndHeader($xAxis)
    {
        $result = [];
        foreach ($xAxis as $line) {
            $result[$line['dataIndex']] =  Str::headline($line['columnIndex']);
        }
        foreach ($result as &$row) {
            $row = "<div class='p-1 text-center'>" . $row . "</div>";
        }
        return $result;
    }
    private function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        if (is_array($items)) $items = collect($items);
        $count = $items->count();

        //<< If current page of HLC is 12, but STW only have 1 page
        //Force the program to select the smallest page
        $page = ($count) ? min(ceil($count / $perPage), $page) : 1; //<< This line has bug
        // Log::info("Count $count, perPage: $perPage, Page: $page");

        return new LengthAwarePaginator($items->forPage($page, $perPage), $count, $perPage, $page, $options);
    }
    private function getEntities(){
        return array_unique( array_map(function ($entity) {
            return $entity->getTable();
        }, Entities::getAll()));
    }
    private function modelYAxis(){
        return Role_set::class;
    }
    private function getTableNameYAxis(){
        return $this->modelYAxis()::getTableName();
    }
    private function getYAxis()
    {
        return $this->modelYAxis()::query()
        ->orderBy('name')
        ->get();;
    }
}
