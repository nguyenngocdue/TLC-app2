<?php

namespace App\Http\Controllers\Reports2;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UpdateUserSettings;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateFormat;
use App\Utils\Support\DateReport;
use App\View\Components\Reports2\TraitReportTableContent;
use Illuminate\Http\Request;
class Rp_reportController extends Controller
{

    use TraitReportTableContent;
    public function updateFilters(Request $request)
    {
        $inputValue = $request->input();
        $rpId = $inputValue['report_id'];
        $rpRoute = route('rp_reports.show', $rpId);
        (new UpdateUserSettings())($request);
        return redirect()->to($rpRoute);
    }

    public function exportExcel(Request $request)
    {
        $input = $request->input();
        $queriedData = json_decode($input['queriedData'], true);
        
        if (empty($queriedData) || !is_array($queriedData)) {
            return response()->json(['error' => 'No data available for export'], 400);
        }
        
        $configuredCols = json_decode($input['configuredCols'], true);

        $columnKeys = array_keys($configuredCols);
        $columnNames = array_map(fn($col) => $col['title'] ?? $col['name'], $configuredCols);
        
        $rows = array_map(fn($data) => array_map(
            function($key) use ($data, $configuredCols) {
                $content = '';
                if (isset($data[$key])) {
                    if(is_array($data[$key])) {
                        $content =  $data[$key]['original_value'] ?? '';
                    } else $content = $data[$key];
                }
                if (isset($configuredCols[$key]) && $configuredCols[$key]) {
                    if (isset($configuredCols['row_renderer']) && $configuredCols['row_renderer'] ===  $this->ROW_RENDERER_DATETIME_ID) {
                        $content = DateFormat::getValueDatetimeByCurrentUser($content);
                    }
                }
                return $content;
            }, 
            $columnKeys), $queriedData);

        $fileName = $input['block_title'].'_'.date('d-m-Y').'.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        return response()->stream(function () use ($rows, $columnNames) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columnNames);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, 200, $headers);
    }

}
