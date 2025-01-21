<?php

namespace App\Listeners\Production;

use App\Http\Services\RoutingLinks\AvgActualHoursForRoutingLinkService;
use App\Models\Prod_routing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateRoutingAvgActualHourListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private AvgActualHoursForRoutingLinkService $avgActualHoursForRoutingLinkService,
    ) {
        //
    }

    public function handle(object $event): void
    {
        $routings = Prod_routing::query()
            ->with(['getLatestProdSequences'])
            ->get();
        foreach ($routings as $routing) {
            if ($routing->getLatestProdSequences) {
                $sequence = $routing->getLatestProdSequences;
                if ($sequence->updated_at->isToday()) {
                    // Log::info($routing);
                    $request = request()->merge(['routing_id' => $routing->id]);
                    Log::channel('schedule_update_routing_avg_actual')
                        ->info("Updating avg actual hours for routing #" . $routing->id . ": " . $routing->name);
                    $this->avgActualHoursForRoutingLinkService->handle($request);
                    // dump("--");
                }
            }
        }
    }
}
