<?php

namespace App\Http\Controllers\Reports2;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UpdateUserSettings;
use App\Models\Rp_report;
use Illuminate\Http\Request;

class Rp_reportController extends Controller
{

    private function formatFilters($keyFilters, $requestInput)
    {
        foreach ($requestInput as $key => &$value) {
            if (in_array($key, $keyFilters)) {
                if (is_string($value)) {
                    $value = (array)$value;
                }
            }
        }
        return $requestInput;
    }
    public function updateFilters(Request $request, $id)
    {
        $report = Rp_report::find($id)->getDeep();
        $filterDetails = $report->getFilterDetails;
        $keyFilters = collect($filterDetails)->map(function ($value) {
            return str_replace('_name', '_id', $value->getColumn?->data_index);
        })->toArray();

        $requestInput = $request->input();
        $params = $this->formatFilters($keyFilters, $requestInput);
        $request->merge($params);
        (new UpdateUserSettings())($request);
        return redirect()->back();
    }
}
