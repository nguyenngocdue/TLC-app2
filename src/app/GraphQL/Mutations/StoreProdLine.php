<?php

namespace App\GraphQL\Mutations;

use App\Models\Prod_line;
use App\Models\Prod_user_run;
use App\Utils\System\GetSetCookie;
use Carbon\Carbon;

final class StoreProdLine
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $dt = Carbon::now();
            $prodLine = Prod_line::create([
                "prod_run_id" => $args['prod_run_id'],
                "date" => $args['date'] ?? $dt->format('Y-m-d'),
                "start" => $args['start'] ?? $dt->format('H:i:s'),
                "end" => $args['end'] ?? null,
                "status" => $args["status"] ?? "running",
                "created_at" => $args["created_at"] ?? null,
                "updated_at" => $args["updated_at"] ?? null,
            ]);
            if (is_array($args['user_ids'])) {
                foreach ($args['user_ids'] as $userId) {
                    $prodUserRun = new Prod_user_run();
                    $prodUserRun->prod_line_id = $prodLine->id;
                    $prodUserRun->user_id = (int)$userId;
                    $prodUserRun->save();
                }
            }
            return 'Complete Create Prod Line and add User to Prod Line Successfully';
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
