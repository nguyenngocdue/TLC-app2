<?php

namespace App\Warehouse;

use App\Http\Controllers\Controller;
use App\Utils\Support\Tree\BuildTree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class Wh_parent extends Controller
{

    protected $tableName = '';
    abstract protected function getSqlStr($userIds, $month);
    private function getDataSource($userIds, $month)
    {
        $sql = $this->getSqlStr($userIds, $month);
        $sqlData = DB::select($sql);
        $collection = collect($sqlData);
        return $collection;
    }

    public function upsertDataWarehouse($month)
    {
        $userIdInput = 2;
        $treeData = BuildTree::getTreeByOptions($userIdInput, '', '', false, false);
        $userIds = collect($treeData)->pluck('id')->toArray();
        $dataQuery = $this->getDataSource($userIds, $month);

        $tableToMark =  DB::table($this->tableName);
        $tableToMark->where('month', $month . '-01')->delete();
        foreach ($dataQuery as $result) {
            $tableToMark->insert((array)$result);
        }
        return $tableToMark;
    }

    public function index(Request $request)
    {
        $month = '2023-05';
        $data = $this->upsertDataWarehouse($month);
        dd($data->get()->toArray());
        return view("welcome-due", [
            // 'nodeTreeArray' => json_encode(array_values($taskTree))
        ]);
    }
}
