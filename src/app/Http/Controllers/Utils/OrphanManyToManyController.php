<?php


namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\System\Memory;
use App\Utils\System\Timer;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrphanManyToManyController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }
    public function get()
    {
        $tableFilterOrphan = 'many_to_many';
        $arrayOrphan1 = $this->getOrphan('doc_type', 'doc_id', $tableFilterOrphan);
        $arrayOrphan1 = $this->filterArrayOrphan($arrayOrphan1);
        $dataSource1 = $this->getDataSource($arrayOrphan1);
        $arrayOrphan2 = $this->getOrphan('term_type', 'term_id', $tableFilterOrphan);
        $arrayOrphan2 = $this->filterArrayOrphan($arrayOrphan2);
        $dataSource2 = $this->getDataSource($arrayOrphan2, 'orphan_term');
        return view("components.orphan.many-to-many", [
            'topTitle' => 'View Orphan',
            'tableFilterOrphan' => $tableFilterOrphan,
            'columns' => $this->getColumnsTable(),
            'dataSource1' => $dataSource1,
            'dataSource2' => $dataSource2,
        ]);
    }
    public function destroy(Request $request)
    {
        try {
            $tableName = $request->table_name;
            if ($dataOrphan = $request->orphan_doc) {
                $this->clearOrphanForDatabase($tableName, $dataOrphan);
            }
            if ($dataOrphan = $request->orphan_term) {
                $this->clearOrphanForDatabase($tableName, $dataOrphan, 'term_type', 'term_id');
            }
            toastr()->success('Clear orphan for table ' . $tableName . 'successfully', 'Clear Orphan');
        } catch (\Throwable $th) {
            toastr()->error($th->getMessage(), 'Clear Orphan');
        }
        return redirect()->back();
    }
    private function filterArrayOrphan($array)
    {
        return array_filter($array, fn ($item) => !empty($item));
    }
    private function clearOrphanForDatabase($tableName, $dataOrphan, $field1 = 'doc_type', $field2 = 'doc_id')
    {
        foreach ($dataOrphan as $value) {
            $docType = $value['doc_type'];
            $docType = preg_replace('/(\\\\+)/', '\\\\\\\\', $docType);
            $docIds = str_replace(' ', '', $value['doc_ids']);
            DB::select("DELETE FROM $tableName WHERE $field1='$docType' AND $field2 IN ($docIds)");
        }
    }
    private function getColumnsTable()
    {
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
    private function getDataSource($arrayOrphan, $tableName = 'orphan_doc')
    {
        $result = [];
        $count = 1;
        foreach ($arrayOrphan as $key => $item) {
            $id = $tableName . '[' . $count . '][doc_type]';
            $value = $tableName . '[' . $count . '][doc_ids]';
            $result[$count]['id'] = $count;
            $result[$count]['doc_type'] = (object)[
                'value' => "<div><input type='hidden' name='$id' value='$key'/>$key</div>",
            ];
            $ids = join(', ', array_values($item));
            $result[$count]['doc_ids'] = (object)[
                'value' => "<div><input type='hidden' name='$value' value='$ids'/>$ids</div>",
            ];
            $count++;

            # code...
        }
        return $result;
    }
    private function getOrphan($field1 = 'doc_type', $field2 = 'doc_id', $table = 'many_to_many')
    {
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
        foreach ($result as $key => $value) {
            $model = new $key;
            $tableName = $model->getTable();
            $ids = array_values($value);
            $idsExist = DB::table($tableName)->whereIn('id', $ids)->pluck('id')->toArray();
            $idsResult = array_diff($ids, $idsExist);
            $result2[$key] = $idsResult;
        }
        return $result2;
    }
}
