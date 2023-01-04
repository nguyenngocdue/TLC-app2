<?php

namespace App\GraphQL\Mutations;

use App\Events\ProdSequenceUpdatedEvent;
use App\Models\Prod_run;
use Carbon\Carbon;

final class UpdateProdRunEvent
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $prodRun = Prod_run::find($args['id']);
            $prodRun->update([
                "date" => $args["date"] ?? $prodRun->date,
                "start" => $args["start"] ?? $prodRun->start,
                "end" => $args["end"] ?? $prodRun->end,
            ]);
            event(new ProdSequenceUpdatedEvent());
            return $prodRun;
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
