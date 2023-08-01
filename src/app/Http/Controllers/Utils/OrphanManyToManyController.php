<?php


namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\System\Memory;
use App\Utils\System\Timer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;

class OrphanManyToManyController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }
    public function get()
    {
       $arrayOrphan1 = $this->getOrphan('doc_type','doc_id');
       $arrayOrphan1 = array_filter($arrayOrphan1, fn($item) => !empty($item));
       $dataSource1 = $this->getDataSource($arrayOrphan1);
       $arrayOrphan2 = $this->getOrphan('term_type','term_id');
       $arrayOrphan2 = array_filter($arrayOrphan2, fn($item) => !empty($item));
       $dataSource2 = $this->getDataSource($arrayOrphan2);

       return view("components.orphan.many-to-many",[
        'topTitle' => 'View Orphan',
        'columns' => $this->getColumnsTable(),
        'dataSource1' => $dataSource1,
        'dataSource2' => $dataSource2,
       ]);
    }
    public function destroy(Request $request){
        dd($request);

    }
    private function getColumnsTable(){
        return [
                [
                    'dataIndex' => 'doc_type',
                    'title' => "Document Type",
                ],
                [
                    'dataIndex' => 'doc_ids',
                    'title' => "Document Id",
                    'width' => 250,
                ],
            ];
    }
    private function getDataSource($arrayOrphan){
        $result = [];
        $count = 1;
        foreach ($arrayOrphan as $key => $value) {
            $result[$count]['id'] = $count;
            $result[$count]['doc_type'] = (object)[
                'value' => $key,
            ];
            $result[$count]['doc_ids'] = (object)[
                'value' => join(', ',array_values($value)),
            ];
            $count++;

            # code...
        }
        return $result;
    }
    private function getOrphan($field1 ='doc_type', $field2 ='doc_id' ,$table = 'many_to_many' ){
        $sql1 = DB::select(
            "SELECT  $field1,$field2,Count(*) as count
            FROM $table GROUP BY $field1, $field2;"
        );
        $result = [];
        foreach ($sql1 as $value) {
            $docType = $value->{$field1};
            if (!isset($result[$docType])) {
                $result[$docType] = [];
            }
            $result[$docType][] = $value->{$field2};
        }
        $result2 = [];
        foreach($result as $key => $value) {
            $model = new $key;
            $tableName = $model->getTable();
            $ids = array_values($value);
            $idsExist = DB::table($tableName)->whereIn('id',$ids)->pluck('id')->toArray();
            $idsResult = array_diff($ids,$idsExist);
            $result2[$key] = $idsResult;
        }
        return $result2;
    }
}
