<?php

namespace App\Console\Commands;

use App\Models\Prod_order;
use App\Models\Prod_routing;
use App\Models\Prod_sequence;
use App\Utils\WorkingShift;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

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

    private function index(Collection $prodSequence, Collection $prodRoutingDetail, $prod_routing_id)
    {
        $index = [];
        foreach ($prodRoutingDetail as $item) {
            $index[$item->prod_routing_id . "-" . $item->prod_routing_link_id] = $item;
        }

        $result = [];
        /** @var Prod_sequence $item */
        foreach ($prodSequence as $item) {
            $value = $index[$prod_routing_id . "-" . $item->prod_routing_link_id];
            $result[$item->prod_order_id . "+" . $item->prod_routing_link_id] = $value;
        }

        return $result;
    }

    private function makeData(Prod_order $po, Prod_routing $pr, $started_at)
    {
        $prodSequence = $po->prodSequences()->orderBy('priority')->orderBy('prod_routing_link_id')->get();
        $prodRoutingDetail = $pr->prodRoutingDetails()->orderBy('priority')->orderBy('prod_routing_link_id')->get();
        $prod_routing_id = $po->prod_routing_id;
        $indexer = $this->index($prodSequence, $prodRoutingDetail, $prod_routing_id);
        // var_dump(array_keys($indexer));

        $workdays = WorkingShift::getWorkdays($started_at);
        // var_dump("WORKDAYS: " . join(", ", $workdays));

        $end = $started_at;
        $updatedCount = 0;
        foreach ($prodSequence as $ps) {
            $key = $ps->prod_order_id . "+" . $ps->prod_routing_link_id;
            $routingDetails = $indexer[$key];
            $target_hours = $routingDetails->target_hours;

            $start = $end;
            $end = WorkingShift::getNextWorkingDateTime($start, $target_hours, $workdays);
            // var_dump($ps->id . " - " . $start . " -> " . $end . ". Target Hours: " . $target_hours);

            $ps->expected_start_at = $start;
            $ps->expected_finish_at = $end;
            $ps->priority = $routingDetails->priority;
            $ps->save();
            $updatedCount++;
        }

        return $updatedCount;
    }

    private function refresh($po_id)
    {
        $this->info("Refreshing PO #$po_id");
        /** @var Prod_order $po */
        $po = Prod_order::find($po_id);
        if (is_null($po)) {
            $this->error("PO #$po_id NOT FOUND.");
            return false;
        }
        /** @var Prod_routing $pr */
        $pr = Prod_routing::find($po->prod_routing_id);
        $started_at = $po->started_at;
        $this->info("Production Order name: " . $po->name . ", start at: " . $started_at);
        $this->info("Production Routing name: " . $pr->name);

        $createdProdSequence = $po->prodSequences()->orderBy('priority')->orderBy('prod_routing_link_id')->get();
        $currentIds = $createdProdSequence->pluck('priority', 'prod_routing_link_id',)->toArray();
        // var_dump($createdProdSequence);
        // var_dump($currentIds);

        $allProdRoutingDetails = $pr->prodRoutingDetails()->orderBy('priority')->orderBy('prod_routing_link_id')->get();
        $allIds = $allProdRoutingDetails->pluck('priority', 'prod_routing_link_id',)->toArray();
        // var_dump($allProdRoutingDetails);
        // var_dump($allIds);

        $toBeAdded = array_diff_key($allIds, $currentIds);
        $toBeObsolete = array_diff_key($currentIds, $allIds);

        // var_dump("toBeAdded", $toBeAdded);
        // var_dump("toBeAdded", join(", ", array_keys($toBeAdded)));
        // var_dump("toBeObsolete", join(", ", array_keys($toBeObsolete)));

        foreach (array_keys($toBeAdded) as $id) {
            Prod_sequence::create([
                'prod_order_id' => $po_id,
                'prod_routing_link_id' => $id,
                'status' => 'new',
                // 'priority' => $priority, //<< This already run in makeData
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

        $po->refresh();
        $updated = $this->makeData($po, $pr, $started_at);
        $this->info("Updated $updated Prod Sequence timelines and priorities.");
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
