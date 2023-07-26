<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Http\Controllers\Controller;
use App\Models\Prod_order;
use App\Models\Qaqc_wir_line;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;

class WirLineController extends Controller
{
    function getProdOrderId($lines)
    {
        if (sizeof($lines) == 0) return null;
        return $lines[0]['prod_order_id'];
    }

    function getRemaining(Request $request)
    {
        $lines = $request->input('lines');

        $prodOrderId = $this->getProdOrderId($lines);
        $prodOrderQuantity = Prod_order::find($prodOrderId)->quantity;

        $ids = array_map(fn ($line) => $line['id'], $lines);
        $rows = Qaqc_wir_line::query()
            ->whereIn('id', $ids)
            ->get();

        $result = [];
        foreach ($rows as $row) {
            $result[] = ['qc_order' => $prodOrderQuantity];
            $prodOrderQuantity -= $row['qc_total'];
        }

        return ResponseObject::responseSuccess(
            $result,
            [$lines, $ids],
            "Return remaining count."
        );
    }
}
