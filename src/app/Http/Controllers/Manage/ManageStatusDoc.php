<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Services\ReadingFileService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManageStatusDoc extends Controller
{
    protected $readingFileService;
    protected $availabeProps = "status_lib";
    protected $manageStatusPost = "manage_status";
    protected $product_id;
    protected $path = "master/manage_status/doc_status.json";
    protected $title = "Manage Doc Status Type";

    public function __construct(ReadingFileService $readingFileService)
    {
        $this->readingFileService = $readingFileService;
    }
    public function index()
    {
        $availabeStatus = $this->readingFileService->indexProps($this->availabeProps, "all_statuses.json");

        $arrayStatusDocType = $this->readingFileService->indexProps($this->manageStatusPost, "doc_status.json");


        // Check orphan status
        $newStatusDocType = [];
        foreach ($arrayStatusDocType as $key => $value) {
            if (empty($availabeStatus[$value]) != true) {
                $newStatusDocType[$value] = $availabeStatus[$value];
            } else {
                $array = ["name" => $value, "title" => $value, "color" => "#EE0000", 'bg-orphan' => "bg-red-500 "];
                $newStatusDocType[$value] = $array;
            }
        }

        $diffStatus = array_diff(array_keys($availabeStatus), array_keys($newStatusDocType));


        $newAvailabeStatus = [];
        foreach ($diffStatus as $key => $value) {
            $newAvailabeStatus[$value] = $availabeStatus[$value];
        }
        $type = $this->title;

        // dd($newAvailabeStatus, $newStatusDocType, $orphanStatusDocType);

        return view('manageStatusDoc')->with(compact('newAvailabeStatus', 'newStatusDocType', 'type'));
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $data = $request->input();
        $typeAction = array_keys(array_slice($data, 1, 2))[0];


        $availabeStatus = $this->readingFileService->indexProps($this->availabeProps, "all_statuses.json");
        $statusDocType = $this->readingFileService->indexProps($this->manageStatusPost, "doc_status.json");

        switch ($typeAction) {
            case 'add':
                $nameStatus = $data['add'];
                $newProps = array_values(array_merge($statusDocType, [$nameStatus]));
                $jsonManage = json_encode($newProps);
                try {
                    $output = Storage::disk('json')->put($this->path, $jsonManage, 'public'); // wwhy output has always false value
                    return redirect()->back()->with(compact('nameStatus'));
                } catch (\Throwable $th) {
                }
                break;
            case 'remove':
                $nameStatus = [$data['remove']];
                $newStatusDocType = array_diff($statusDocType, $nameStatus);
                $jsonManage = json_encode(array_values($newStatusDocType));

                try {
                    $output = Storage::disk('json')->put($this->path, $jsonManage, 'public'); // wwhy output has always false value
                    return redirect()->back()->with(compact('nameStatus'));
                } catch (\Throwable $th) {
                }
                break;
            case 'move_up':
                $nameStatus = $data['move_up'];
                $docType = $statusDocType;
                $index = array_search($nameStatus, $docType);
                if ($index > 0) {
                    [$docType[$index - 1], $docType[$index]] = [$docType[$index], $docType[$index - 1]];
                } else {
                    unset($docType[$index]);
                    array_push($docType, $nameStatus);
                }
                // dd($docType, $index, $nameStatus);
                $jsonManage = json_encode(array_values($docType));
                try {
                    $output = Storage::disk('json')->put($this->path, $jsonManage, 'public'); // wwhy output has always false value
                    return redirect()->back();
                } catch (\Throwable $th) {
                }
                break;
            case 'move_down':
                $nameStatus = $data['move_down'];
                $docType = $statusDocType;
                $lenDocType = count($docType);

                $index = array_search($nameStatus, $docType);

                if ($index < $lenDocType - 1) {
                    [$docType[$index + 1], $docType[$index]] = [$docType[$index], $docType[$index + 1]];
                } else {
                    unset($docType[$index]);
                    array_splice($docType, 0, 0, $nameStatus);
                }
                $jsonManage = json_encode(array_values($docType));
                try {
                    $output = Storage::disk('json')->put($this->path, $jsonManage, 'public'); // wwhy output has always false value
                    return redirect()->back();
                } catch (\Throwable $th) {
                }
                break;
            default:
                break;
        }
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
