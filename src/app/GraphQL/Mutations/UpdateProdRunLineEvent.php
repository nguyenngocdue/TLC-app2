<?php

namespace App\GraphQL\Mutations;

use App\Events\ProdRunUpdatedEvent;
use App\Models\Prod_run_line;
use Carbon\Carbon;

final class UpdateProdRunLineEvent
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $prodRunLine = Prod_run_line::find($args['id']);
            $prodRunLine->update([
                "date" => $args["date"] ?? $prodRunLine->date,
                "start" => $args["start"] ?? $prodRunLine->start,
                "end" => $args["end"] ?? $prodRunLine->end,
            ]);
            event(new ProdRunUpdatedEvent());
            return $prodRunLine;
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
