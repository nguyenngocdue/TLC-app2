<?php

namespace App\Services\Qaqc_insp_chklst_sht_sig;

use App\Repositories\Qaqc_insp_chklst_sht_sig\Qaqc_insp_chklst_sht_sigRepositoryInterface;
use App\Services\BaseService;

class Qaqc_insp_chklst_sht_sigService extends BaseService implements Qaqc_insp_chklst_sht_sigServiceInterface
{
    protected $qaqcInspChklstShtSigRepository;

    public function __construct(Qaqc_insp_chklst_sht_sigRepositoryInterface $qaqcInspChklstShtSigRepository)
    {
        $this->qaqcInspChklstShtSigRepository = $qaqcInspChklstShtSigRepository;
    }
    public function create($request)
    {
        $value = $request->value;
        $ownerId = $request->owner_id;
        $qaqcInspChklstShtId = $request->qaqc_insp_chklst_sht_id;
        return $this->qaqcInspChklstShtSigRepository->create([
            'value' => $value,
            'owner_id' => $ownerId,
            'qaqc_insp_chklst_sht_id' => $qaqcInspChklstShtId,
        ]);
    }
    public function update($id, $request)
    {
        return $this->qaqcInspChklstShtSigRepository->update($id, $request->input());
    }
    public function delete($id)
    {
        $sig = $this->qaqcInspChklstShtSigRepository->find($id);
        if ($sig->owner_id == auth()->user()->id) {
            $this->repository->delete($id);
            return true;
        }
        return false;
    }
}
