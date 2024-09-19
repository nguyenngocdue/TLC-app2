<?php

namespace App\Http\Controllers\Reports2;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UpdateUserSettings;
use Illuminate\Http\Request;
class Rp_reportController extends Controller
{

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

        $rows = array_map(fn($data) => array_map(fn($key) => $data[$key] ?? '', $columnKeys), $queriedData);
        // dd($input);

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
