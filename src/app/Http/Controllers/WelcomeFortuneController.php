<?php

namespace App\Http\Controllers;

use App\Http\Services\ProdSequenceToProdOrderService;
use App\Models\Prod_order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WelcomeFortuneController extends Controller
{
    function getType()
    {
        return "dashboard";
    }
    public function index(Request $request)
    {

        // $allOrders = Prod_order::query()
        //     ->with("getProdSequences")
        //     ->get();
        // $service = new ProdSequenceToProdOrderService();
        // $count = 0;
        // foreach ($allOrders as $order) {
        //     if (is_null($order->prod_sequence_progress)) {
        //         $sequences = $order->getProdSequences;
        //         if (isset($sequences[0])) {
        //             dump("Updating " . $order->id . " " . $sequences[0]->id);
        //             $service->update($sequences[0]->id);
        //             $count++;
        //             if ($count > 100) break;
        //         }
        //     }
        // }
        return view("welcome-fortune", []);
    }
}
