<?php

namespace App\GraphQL\Mutations;

use App\Models\Prod_run_line;
use Carbon\Carbon;

final class StoreProdRunLine
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $dt = Carbon::now();
            $prodLine = Prod_run_line::create([
                "prod_run_id" => $args['prod_run_id'],
                "date" => $args['date'] ?? $dt->format('Y-m-d'),
                "start" => $args['start'] ?? $dt->format('H:i:s'),
                "end" => $args['end'] ?? null,
                "created_at" => $args["created_at"] ?? null,
                "updated_at" => $args["updated_at"] ?? null,
            ]);
            if (is_array($args['user_ids'])) {
                $prodLine->users()->attach($args['user_ids']);
            }
            return [
                'id' => $prodLine->id,
                'status' => 'Complete Create Prod Line and add User to Prod Line Successfully'
            ];
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
