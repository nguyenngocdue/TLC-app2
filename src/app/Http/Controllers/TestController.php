<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\Support\GetAllEntities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function PHPSTORM_META\map;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entities = GetAllEntities::getAllEntities();
        $entitiesTablePlural = [];
        $entitiesTableSingular = [];
        foreach ($entities as $entity) {
            array_push($entitiesTablePlural, $entity->getTable());
            array_push($entitiesTableSingular, Str::singular($entity->getTable()));
        }
        $data = $this->pathConfig('sidebarProps');
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
        $dataAdminUpdate = [];
        foreach ($valueAdminUpdate as $value) {
            $var =
                [
                    "title" => "$value Management",
                    "icon" => "nav-icon fas fa-user",
                    "href_parent" => "dashboard/$value",
                    "items" => [
                        [

                            "title" => "All $value",
                            "icon" => "fas fa-users",
                            "href" => "dashboard/$value/{$value}_renderprop"
                        ],
                        [

                            "title" => "Add New",
                            "icon" => "fas fa-user",
                            "href" => "dashboard/$value/{$value}_addnew"
                        ]
                    ]
                ];
            $data['admin_zone']['items'][$value] = $var;
        }
        foreach ($valueDevUpdate as $value) {
            $var =  [
                "title" => "$value Management",
                "icon" => "nav-icon fas fa-user",
                "href_parent" => "propman/$value",
                "items" => [
                    [

                        "title" => "Manage Prop",
                        "icon" => "fas fa-project-diagram",
                        "href" => "propman/$value/{$value}_manageprop"
                    ],
                    [

                        "title" => "Manage Line",
                        "icon" => "fas fa-grip-lines",
                        "href" => "propman/$value/{$value}_managelineprop"
                    ],
                    [

                        "title" => "Manage Relationship",
                        "icon" => "fas fa-heart",
                        "href" => "propman/$value/{$value}_managerelationship"
                    ]
                ]
            ];
            $data['developer_zone']['items'][$value] = $var;
        }
        $this->updateConfig('sidebarProps', $data);



        return view('test', [
            'title' => 'Title'
        ]);
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
        $output = Storage::disk('json')->put("configs/view/dashboard/$fileName.json", json_encode($data), 'public');
        if ($output) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
