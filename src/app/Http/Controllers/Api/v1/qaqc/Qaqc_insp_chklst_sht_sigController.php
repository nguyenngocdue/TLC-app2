<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Http\Controllers\Controller;
use App\Services\Qaqc_insp_chklst_sht_sig\Qaqc_insp_chklst_sht_sigServiceInterface;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;

class Qaqc_insp_chklst_sht_sigController extends Controller
{
    protected $service;
    public function __construct(Qaqc_insp_chklst_sht_sigServiceInterface $service)
    {
        $this->service  = $service;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $sig = $this->service->create($request);
            return ResponseObject::responseSuccess(
                $sig,
                [],
                'Created signature external successfully'
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $sig = $this->service->update($id, $request);
            return ResponseObject::responseSuccess(
                $sig,
                [],
                'Updated signature external successfully'
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $isSuccess = $this->service->delete($id);
            if ($isSuccess) {
                return ResponseObject::responseSuccess(
                    null,
                    [],
                    'Deleted signature external successfully'
                );
            }
            return ResponseObject::responseFail(
                'You are not the creator of the signature, so you cannot delete it!',
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
}
