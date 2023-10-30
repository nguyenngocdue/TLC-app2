<?php

namespace App\Console\Commands;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFormula;
use App\Models\Esg_master_sheet;
use App\Models\Esg_sheet;
use App\Models\Esg_sheet_line;
use App\Models\Esg_tmpl;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CloneTemplateForEsgSheetCommand extends Command
{
    // use CloneRunTrait;
    use TraitEntityFormula;

    protected $type;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ndc:cloneEsgSheet 
    {--ownerId= : ID of current user}
    {--tmplId= : template ID}
    {--masterSheetId= : master sheet id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new ESG Master sheet by cloning a template sheet.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ownerId = $this->input->getOption('ownerId');
        $masterSheetId = $this->input->getOption("masterSheetId");
        $masterSheet = Esg_master_sheet::findOrFail($masterSheetId);

        $tmplId = $this->input->getOption("tmplId");
        $tmplSht = Esg_tmpl::findOrFail($tmplId);

        if (!$ownerId) {
            $this->info("Owner ID:{$ownerId} doesn't exist");
            return Command::FAILURE;
        }
        if (!$masterSheet) {
            $this->info("Master Sheet ID:{$masterSheetId} doesn't exist");
            return Command::FAILURE;
        }
        if (!$tmplSht) {
            $this->info("Template Sheet ID:{$tmplSht} doesn't exist");
            return Command::FAILURE;
        }
        try {
            $newSheet = Esg_sheet::create([
                'esg_master_sheet_id' => $masterSheetId,
                'esg_tmpl_id' => $tmplId,
                'owner_id' => $ownerId,
            ]);

            $tmplLines = $tmplSht->getLines;
            foreach ($tmplLines as $line) {
                $newLine = $line->toArray();
                $newLine['esg_sheet_id'] = $newSheet->id;
                unset($newLine['id']);
                // Log::info($newLine);
                Esg_sheet_line::create($newLine);
            }

            //<<This id will be use to redirect, please don't add more text into it.
            $this->info($newSheet->id);
            return Command::SUCCESS;
        } catch (\Throwable $th) {

            $this->error($th->getMessage());
            // $this->error($th->getMessage());
            return Command::FAILURE;
        }
    }
}
