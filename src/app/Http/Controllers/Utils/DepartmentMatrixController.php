<?php


namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Utils\ClassList;
use Illuminate\Http\Request;

class DepartmentMatrixController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $data = [
            26 => [],
            22 => [26],
            24 => [22],
            23 => [22, 24],
            25 => [22, 24, 23],
            3 => [22, 24, 23, 25],
            39 => [22, 24, 23, 25, 3],
            8 => [22, 24, 23, 3],
            12 => [22, 24, 23, 3, 8],
            40 => [],
            31 => [3, 8, 12],
            13 => [31],
            38 => [3, 8, 12, 31, 13],
            37 => [26, 22, 24, 23, 25, 3, 39, 8, 12, 40, 31, 13, 38],
            1 => [26, 22, 24, 23, 25, 3, 39, 8, 12, 40, 31, 13, 38, 37],
            41 => [26, 22, 24, 23, 25, 3, 39, 8, 12, 40, 31, 13, 38, 37, 1],
        ];

        $departments = Department::query()
            ->whereNull('hide_in_survey')
            ->orWhere('hide_in_survey', 0)
            ->orWhere('hide_in_survey', '')
            ->orderBy('name')
            ->get()
            ->pluck('name', 'id');
        // dump($departments);

        $columns = $departments->map(fn ($i, $index) => ['dataIndex' => $index, 'title' => $i . " (#$index)"]);
        $columns->prepend(['dataIndex' => 'name']);
        // dump($columns);

        $dataSource = [];
        foreach ($departments as $index => $department) {
            $item = [
                'name' => (object)[
                    'value' => $department . " (#$index)",
                    'cell_class' => "whitespace-nowrap",
                ],
                $index => (object)[
                    'value' => '',
                    'cell_class' => 'bg-black'
                ],
                // 'width' => 120,
            ];

            $dataSource[$index] = $item;
        }

        $checked = (object)[
            'value' => "",
            'cell_class' => 'text-center bg-green-400',
        ];

        foreach ($data as $source => $targetArray) {
            if (!isset($dataSource[$source])) {
                $dataSource[$source]['name'] =  "(#$source)";
            }
            foreach ($targetArray as $target) {
                $dataSource[$source][$target] = $checked;
                $dataSource[$target][$source] = $checked;
            }
        }

        return view("utils/department-matrix", [
            'columns' => $columns->toArray(),
            'dataSource' => $dataSource,
        ]);
    }
}
