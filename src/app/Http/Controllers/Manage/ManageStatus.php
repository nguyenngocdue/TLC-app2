<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Services\ReadingFileService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManageStatus extends Controller
{
    protected $readingFileService;
    protected $availabeProps = "statusLibary";
    protected $manageStatusPost = "manageStatusPost";
    protected $product_id;
    public function __construct(ReadingFileService $readingFileService)
    {
        $this->readingFileService = $readingFileService;
    }

    public function index()
    {
        $availabeStatus = $this->readingFileService->indexProps($this->availabeProps);
        $statusPostType = $this->readingFileService->indexProps($this->manageStatusPost);

        $diffStatus = array_diff(array_keys($availabeStatus), array_keys($statusPostType));

        $newAvailabeStatus = [];
        foreach ($diffStatus as $key => $value) {
            $newAvailabeStatus[$value] = $availabeStatus[$value];
        }
        return view('manageStatus')->with(compact('newAvailabeStatus', 'statusPostType'));
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $data = $request->input();
        $typeAction = array_keys(array_slice($data, 1, 2))[0];


        $availabeStatus = $this->readingFileService->indexProps($this->availabeProps);
        $statusPostType = $this->readingFileService->indexProps($this->manageStatusPost);

        switch ($typeAction) {
            case 'add':
                $nameStatus = $data['add'];
                $newStatus = [$nameStatus => $availabeStatus[$nameStatus]];
                $newProps = array_merge($statusPostType, $newStatus);
                $jsonManage = json_encode(array_merge($newProps));

                try {
                    $output = Storage::disk('json')->put("entities/$this->manageStatusPost/props.json", $jsonManage, 'public'); // wwhy output has always false value
                    // dd($nameStatus);
                    return redirect()->back()->with(compact('nameStatus'));
                } catch (\Throwable $th) {
                }
                break;
            case 'remove':
                $nameStatus = $data['remove'];

                unset($statusPostType[$nameStatus]);
                $jsonManage = json_encode($statusPostType);
                try {
                    $output = Storage::disk('json')->put("entities/$this->manageStatusPost/props.json", $jsonManage, 'public'); // wwhy output has always false value
                    return redirect()->back()->with(compact('nameStatus'));
                } catch (\Throwable $th) {
                }
                break;
            case 'move_up':
                $nameStatus = $data['move_up'];
                $keyStaus = array_keys($statusPostType);
                $indexStatus = array_flip($keyStaus);

                // dd($keyStaus, $indexStatus, $indexStatus[$nameStatus] - 1);

                if ($indexStatus[$nameStatus] - 1 >= 0) {
                    [$keyStaus[$indexStatus[$nameStatus] - 1], $keyStaus[$indexStatus[$nameStatus]]] = [$keyStaus[$indexStatus[$nameStatus]], $keyStaus[$indexStatus[$nameStatus] - 1]];
                } else {
                    [$keyStaus[$indexStatus[$nameStatus]], $keyStaus[count($keyStaus) - 1]] = [$keyStaus[count($keyStaus) - 1], $keyStaus[$indexStatus[$nameStatus]]];
                }


                $newAvailabeStatus = [];
                foreach ($keyStaus as $key => $value) {
                    $newAvailabeStatus[$value] = $statusPostType[$value];
                }
                $jsonManage = json_encode($newAvailabeStatus);
                try {
                    $output = Storage::disk('json')->put("entities/$this->manageStatusPost/props.json", $jsonManage, 'public'); // wwhy output has always false value
                    return redirect()->back();
                } catch (\Throwable $th) {
                }
                break;
            case 'move_down':
                $nameStatus = $data['move_down'];
                $keyStaus = array_keys($statusPostType);
                $indexStatus = array_flip($keyStaus);

                // dd($keyStaus, $indexStatus, $indexStatus[$nameStatus]);

                if ($indexStatus[$nameStatus] + 1 < count($keyStaus)) {
                    [$keyStaus[$indexStatus[$nameStatus] + 1], $keyStaus[$indexStatus[$nameStatus]]] = [$keyStaus[$indexStatus[$nameStatus]], $keyStaus[$indexStatus[$nameStatus] + 1]];
                } else {
                    [$keyStaus[$indexStatus[$nameStatus]], $keyStaus[0]] = [$keyStaus[0], $keyStaus[$indexStatus[$nameStatus]]];
                }
                $newAvailabeStatus = [];
                foreach ($keyStaus as $key => $value) {
                    $newAvailabeStatus[$value] = $statusPostType[$value];
                }
                $jsonManage = json_encode($newAvailabeStatus);
                try {
                    $output = Storage::disk('json')->put("entities/$this->manageStatusPost/props.json", $jsonManage, 'public'); // wwhy output has always false value
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
