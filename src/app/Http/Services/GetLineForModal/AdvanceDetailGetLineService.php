<?php

namespace App\Http\Services\GetLineForModal;

use App\Models\Act_advance_req;
use App\Models\Fin_expense_claim_adv_detail;
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

        $claimed_lines = Fin_expense_claim_adv_detail::query()
            ->where('user_id', $user_id)
            ->get()
            ->pluck('act_advance_req_id');

        $lines = Act_advance_req::query()
            ->where('user_id', $user_id)
            ->whereNotIn('id', $claimed_lines)
            ->get();

        foreach ($lines as $line) {
            $formatted_amount = number_format($line->advance_amount_lcy, 2, '.', ',');
            $item = [
                'id' => 'adv_req_' . $line->id,
                'parent' => "#",
                'text' => "<span class='w-1/4'>" . $line->title . " | " . $line->title_vn . ": </span>" .  $formatted_amount . " VND",
                'data' => [
                    'type' => 'adv_line',
                    'user_id' => $line->user_id,
                    'employee_id' => $line->employee_id,
                    'act_advance_req_id' => $line->id,
                    'adv_date' => $line->created_at,
                    'adv_amount_lcy' => $line->advance_amount_lcy,
                    'adv_currency_id' => $line->counter_currency_id,
                    'adv_reason' => $line->description,
                ],
            ];
            $result[] = $item;
        }

        return $result;
    }
}
