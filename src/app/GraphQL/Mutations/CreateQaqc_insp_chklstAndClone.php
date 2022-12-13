<?php

namespace App\GraphQL\Mutations;

use App\Models\Qaqc_insp_chklst;
use Illuminate\Support\Facades\Artisan;

final class CreateQaqc_insp_chklstAndClone
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $newQaqcInspChklst = Qaqc_insp_chklst::create([
                'prod_order_id' => $args['prod_order_id'] ?? null,
                'name' => $args['name'] ?? null,
                'description' => $args['description'] ?? null,
                'owner_id' => $args['owner_id'] ?? null,
                'consent_number' => $args['consent_number'] ?? null,
                'slug' => $args['slug'] ?? null,
                'progress' => $args['progress'] ?? null,
            ]);
            $isSuccess = Artisan::call('ndc:clone', ['--idTmpl' => $args['idTmpl'], '--idChklst' => $newQaqcInspChklst->id]);
            return $isSuccess === 0 ?  [
                'qaqc_insp_chklst' => $newQaqcInspChklst,
                'status' => 'Clone and Create Successfully',
            ] :  [
                'qaqc_insp_chklst' => $newQaqcInspChklst,
                'status' => 'Clone and Create Failed',
            ];
        } catch (\Throwable $th) {
            return [
                'qaqc_insp_chklst' => null,
                'status' => $th,
            ];
        }
    }
}
