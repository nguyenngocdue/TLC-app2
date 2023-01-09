<?php

namespace App\Console\Commands;

use App\Models\Prod_order;
use App\Models\Prod_routing;
use App\Models\Prod_sequence;
use Illuminate\Console\Command;

class RefreshPOCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tlc:refreshPO
    {po_id : The ID of the Production Order}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate expected_started_at and expected_finished_at of production_sequences Table';

    private function refresh($po_id)
    {
        $this->info("Refreshing PO with id=$po_id");
        /** @var Prod_order $po */
        $po = Prod_order::find($po_id);
        /** @var Prod_routing $pr */
        $pr = Prod_routing::find($po->prod_routing_id);
        $started_at = $po->started_at;
        $this->info("Production Order name: " . $po->name . ", start at: " . $started_at);
        $this->info("Production Routing name: " . $pr->name);

        $createdProdSequence = $po->prodSequences;
        $currentIds = $createdProdSequence->pluck('prod_routing_link_id')->toArray();
        // var_dump(join(", ", $currentIds));

        $allProdRoutingLinks = $pr->prodRoutingLinks;
        $allIds = $allProdRoutingLinks->pluck('id')->toArray();
        // var_dump(join(", ", $allIds));

        $toBeAdded = array_diff($allIds, $currentIds);
        $toBeObsolete = array_diff($currentIds, $allIds);

        // var_dump(join(", ", $toBeAdded));
        // var_dump(join(", ", $toBeObsolete));

        foreach ($toBeAdded as $id) {
            Prod_sequence::create([
                'prod_order_id' => $po_id,
                'prod_routing_link_id' => $id,
                'status' => 'new',
            ]);
        }
        $this->info("Created " . count($toBeAdded) . " new Prod Sequences.");

        foreach ($createdProdSequence as $item) {
            if (in_array($item->prod_routing_link_id, $toBeObsolete)) {
                $ps = Prod_sequence::find($item->id);
                $ps->transitionTo('obsolete');
            }
        }
        $this->info("Transitioned " . count($toBeObsolete) . " Prod Sequences to Obsolete.");
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $po_id = $this->argument('po_id');
        $this->refresh($po_id);

        return Command::SUCCESS;
    }
}
