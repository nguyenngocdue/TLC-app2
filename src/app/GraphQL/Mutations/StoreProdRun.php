<?php

namespace App\GraphQL\Mutations;

use App\Events\ProdSequenceUpdatedEvent;
use App\Models\Prod_run;
use Carbon\Carbon;

final class StoreProdRun
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $dt = Carbon::now();
            $prodRun = Prod_run::create([
                "prod_sequence_id" => $args['prod_sequence_id'],
                "owner_id" => $args['owner_id'],
                "date" => $args['date'] ?? $dt->format('Y-m-d'),
                "start" => $args['start'] ?? $dt->format('H:i:s'),
                "end" => $args['end'] ?? null,
                "created_at" => $args["created_at"] ?? null,
                "updated_at" => $args["updated_at"] ?? null,
            ]);
            if (is_array($args['user_ids'])) {
                $prodRun->getUsers()->attach($args['user_ids']);
            }
            event(new ProdSequenceUpdatedEvent());
            return [
                'id' => $prodRun->id,
                'status' => 'Complete Create Prod Line and add User to Prod Line Successfully'
            ];
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
