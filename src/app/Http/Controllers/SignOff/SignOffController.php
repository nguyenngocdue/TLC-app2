<?php

namespace App\Http\Controllers\SignOff;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SignOff\Trait\TraitSupportSignOff;
use App\Models\Qaqc_insp_chklst_run_line;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SignOffController extends Controller
{
    use TraitSupportSignOff;
    public function getType()
    {
        return 'dashboard';
    }
    public function index(Request $request, $id)
    {
        $dataSource = $this->getDataSource($id);
        $tableDataSource = $this->transformDataSource($dataSource[0]);
        return view('sign-off.index', [
            'tableColumns' => $this->getTableColumns(),
            'tableDataSource' => $tableDataSource,
            'headerDataSource' => $dataSource[1],
        ]);
    }
    public function update(Request $request, $id)
    {
        try {
            Qaqc_insp_chklst_run_line::findOrFail($id)->update([
                'value' => $request->input('signature'),
                'inspector_id' => auth()->user()->id,
            ]);
            return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::warning($th, 'Update Signature Failed');
            //throw $th;
        }
    }
}
