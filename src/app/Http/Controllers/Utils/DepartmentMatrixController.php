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
        $data = config("departments.related");

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
