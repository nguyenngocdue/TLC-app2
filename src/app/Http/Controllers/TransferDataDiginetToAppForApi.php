<?php

namespace App\Http\Controllers;

use App\Http\Services\DiginetGetEmployeeHoursService;
use App\Models\Diginet_employee_hours;
use App\Utils\Support\APIDiginet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;

class TransferDataDiginetToAppForApi
{

    public function store(Request $request)
    {
        $params = $request->input();
        $data = APIDiginet::getDatasourceFromAPI("employee-hours", $params)['data'];
        $fn = new DiginetGetEmployeeHoursService();
        $year = substr($params['FromDate'], 0, 4);
        $month = substr($params['FromDate'], 5, 2);

        if ($data) {
            $fieldsToMap = ['employeeid', 'employee_name', 'company_code', 'workplace_code', 'date', 'standard_hours', 'actual_working_hours', 'ot_hours', 'la_hours', 'business_trip_hours', 'work_from_home_hours'];

            DB::statement('ALTER TABLE diginet_employee_hours AUTO_INCREMENT = 1;');
            DB::table('diginet_employee_hours')
                ->whereRaw('YEAR(date) = ? AND MONTH(date) = ?', [$year, $month])
                ->delete();

            foreach ($data as &$item) $item = $fn->changeFields($item, $fieldsToMap);
            $recordCount = 0;
            foreach ($data as $row) {
                Diginet_employee_hours::create($row);
                $recordCount++;
            }
            // Toastr::success("Successfully added {$recordCount} rows to the database1.");
            session()->flash('toastr', json_encode([
                'type' => 'success',
                'message' => "Successfully added {$recordCount} rows to the database."
            ]));
            return redirect()->route('employee_hours.index');
        } else {
            dd("Empty data");
        }
    }
}
