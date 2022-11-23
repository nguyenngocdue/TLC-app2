<?php

namespace App\Http\Controllers;

use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // public function update(Request $request, $id)
    // {
    //     dd($request->all(), $id);
    //     $data = $request->input();
    //     $data = array_diff_key($data, ['_token' => '', '_method' => 'PUT']);
    //     $user = User::find($id);
    //     $dataSettings = [];
    //     foreach ($data as $key => $value) {
    //         $array = explode('|', $key);
    //         $dataSettings[$array[0]][$array[1]] = $value;
    //     }
    //     $user->settings = $dataSettings;
    //     $user->update();
    //     Toastr::success('Save settings json Users successfully', 'Save file json');
    //     return redirect()->back();
    // }
}
