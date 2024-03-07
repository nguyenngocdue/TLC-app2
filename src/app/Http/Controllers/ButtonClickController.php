<?php

namespace App\Http\Controllers;

use App\Events\ButtonClickEvent;
use Illuminate\Http\Request;

class ButtonClickController extends Controller
{
    public function handleClickEvent(Request $request)
    {
        // Fire the event when the button is clicked
        event(new ButtonClickEvent());

        // Return a JSON response with a message
        // return response()->json(['message' => 'Event triggered successfully'], 200);
    }
}
