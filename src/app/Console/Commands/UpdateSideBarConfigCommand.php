<?php

namespace App\Console\Commands;

use App\Http\Services\Manage\ManageService;
use App\Utils\Support\Entities;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateSideBarConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ndc:update-sidebar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update sidebar configuration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $data = $this->pathConfig('sidebarProps');
            if (!$data) return;
            $entities = Entities::getAll();
            $entitiesTablePlural = [];
            $entitiesTableSingular = [];
            foreach ($entities as $entity) {
                array_push($entitiesTablePlural, $entity->getTable());
                array_push($entitiesTableSingular, Str::singular($entity->getTable()));
            }
            $adminZone = array_keys($data['admin_zone']['items']);
            $devZone = array_keys($data['developer_zone']['items']);
            $valueAdminUpdate = array_diff($entitiesTablePlural, $adminZone);
            $valueAdminUpdate2 = array_diff($adminZone, $entitiesTablePlural);
            if (count($valueAdminUpdate2) > 0) {
                array_map(
                    function ($value) use (&$data) {
                        unset($data['admin_zone']['items'][$value]);
                    },
                    $valueAdminUpdate2
                );
            };
            $valueDevUpdate = array_diff($entitiesTableSingular, $devZone);
            $valueDevUpdate2 = array_diff($devZone, $entitiesTableSingular);
            if (count($valueDevUpdate2) > 0) {
                array_map(
                    function ($value) use (&$data) {
                        unset($data['developer_zone']['items'][$value]);
                    },
                    $valueDevUpdate2
                );
            };
            if (count($valueAdminUpdate) > 0) {
                foreach ($valueAdminUpdate as $value) {
                    $ucfirst = Str::ucfirst($value);
                    $var =
                        [
                            "title" => "$ucfirst",
                            "icon" => "nav-icon fas fa-user",
                            "href_parent" => "dashboard/$value",
                            "items" => [
                                [

                                    "title" => "All $value",
                                    "icon" => "fas fa-users",
                                    "href" => "dashboard/$value/{$value}_render"
                                ],
                                [

                                    "title" => "Add New",
                                    "icon" => "fas fa-user",
                                    "href" => "dashboard/$value/{$value}_addnew"
                                ],

                            ]
                        ];
                    $data['admin_zone']['items'][$value] = $var;
                }
            }
            if (count($valueDevUpdate) > 0) {
                foreach ($valueDevUpdate as $value) {
                    $ucfirst = Str::ucfirst($value);
                    $var =  [
                        "title" => "$ucfirst",
                        "icon" => "nav-icon fas fa-user",
                        "href_parent" => "propman/$value",
                        "items" => [
                            [

                                "title" => "Manage Prop",
                                "icon" => "fas fa-project-diagram",
                                "href" => "propman/$value/{$value}_mngprop"
                            ],
                            [

                                "title" => "Manage Line",
                                "icon" => "fas fa-grip-lines",
                                "href" => "propman/$value/{$value}_mnglnprop"
                            ],
                            [

                                "title" => "Manage Relationship",
                                "icon" => "fas fa-heart",
                                "href" => "propman/$value/{$value}_mngrls"
                            ]
                        ]
                    ];
                    $data['developer_zone']['items'][$value] = $var;
                }
            }
            if (!$this->updateConfig('sidebarProps', $data)) $this->error("Update failed");
            $this->info('Update sidebar Props successfully');
        } catch (\Throwable $th) {
            //throw $th;
            $this->error($th->getMessage());
        }
    }
    protected function pathConfig($fileName)
    {
        $path = storage_path() . "/json/configs/view/dashboard/$fileName.json";
        if (file_exists($path)) {
            $dataManage = json_decode(file_get_contents($path), true);
            return $dataManage;
        } else {
            return false;
        }
    }
    protected function updateConfig($fileName, $data)
    {
        $output = Storage::disk('json')->put("configs/view/dashboard/$fileName.json", json_encode($data, JSON_PRETTY_PRINT), 'public');
        if ($output) {
            return true;
        } else {
            return false;
        }
    }
}
