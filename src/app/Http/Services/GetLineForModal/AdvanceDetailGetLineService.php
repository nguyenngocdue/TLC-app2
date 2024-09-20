<?php

namespace App\Http\Services\GetLineForModal;

use App\Models\Act_advance_req;
use App\Models\Diginet_business_trip_line;
use App\Models\Fin_expense_claim_travel_detail;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdvanceDetailGetLineService
{
    public function getLines(Request $request)
    {
        // Log::info($request);
        $user_id = $request->user_id;
        // Log::info("Employee ID: " . $employeeId);
        $result = [];

        $lines = Act_advance_req::query()
            ->where('user_id', $user_id)
            ->get();

        foreach ($lines as $line) {
            $item = [
                'id' => 'adv_req_' . $line->id,
                'parent' => "#",
                'text' => $line->title . " | " . $line->title_vn . ": " . $line->advance_amount,
                'data' => [
                    'type' => 'adv_line',
                    'user_id' => $line->user_id,
                    'employee_id' => $line->employee_id,
                    'act_adv_req_id' => $line->id,
                    'adv_date' => $line->created_at,
                    'adv_amount' => $line->advance_amount,
                    'adv_currency_id' => $line->counter_currency_id,
                    'adv_reason' => $line->description,
                ],
            ];
            $result[] = $item;
        }

        return $result;
    }
}
