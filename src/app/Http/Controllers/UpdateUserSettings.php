<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\Support\CurrentUser;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdateUserSettings extends Controller
{
    private function updatePerPage($request, $settings)
    {
        $perPage = $request->input('page_limit');
        $type = $request->input("_entity");
        $settings[$type]['page_limit'] = $perPage;
        return $settings;
    }

    private function updateGear($request, $settings)
    {
        [$type, $toBeInserted] = $this->formatRequestValue($request);
        $settings[$type]['columns'] = $toBeInserted;
        return $settings;
    }
    private function updateFilter($request, $settings)
    {
        [$type, $valueRequest] = $this->formatRequestValue($request);
        $settings[$type]['advance_filters'] = $valueRequest;
        return $settings;
    }
    private function clearFilter($request, $settings)
    {
        [$type,] = $this->formatRequestValue($request);
        unset($settings[$type]);
        return $settings;
    }
    private function formatRequestValue($request)
    {
        $all = $request->all();
        $type = $all['_entity'];
        $toBeInserted = array_diff_key($all, array_flip(['_method', '_token', '_entity', 'action']));
        return [$type, $toBeInserted];
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $action = $request->input('action');
        $user = User::find(Auth::id());
        $settings = $user->settings;

        switch ($action) {
            case 'updatePerPage':
                $settings = $this->updatePerPage($request, $settings);
                break;
            case 'updateGear':
                $settings = $this->updateGear($request, $settings);
                break;
            case 'updateAdvanceFilter':
                $settings = $this->updateFilter($request, $settings);
                break;
            case 'clearAdvanceFilter':
                $settings = $this->clearFilter($request, $settings);
                break;
            default:
                Log::error("Unknown action $action");
                break;
        }
        $user->settings = $settings;
        $user->update();
        Toastr::success('User Settings Saved Successfully', 'Successfully');
        return redirect()->back();
    }
}
