<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Services\ReadingFileService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

abstract class ManageStatusDocType extends Controller
{
    protected $title;
    protected $w_file_path;
    protected $r_file_path;
    protected $disk = "json";

    public function __construct(ReadingFileService $readingFileService)
    {
        $this->readingFileService = $readingFileService;
    }
    public function index()
    {
        $availabeStatus = $this->readingFileService->getPath("$this->disk/$this->r_file_path");
        $arrayStatusDocType = $this->readingFileService->getPath("$this->disk/$this->w_file_path");

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

        // dd($newAvailabeStatus, $newStatusDocType);

        return view('manageStatusDoc')->with(compact('newAvailabeStatus', 'newStatusDocType', 'type'));
    }


    public function store(Request $request)
    {
        $data = $request->input();
        $typeAction = array_keys(array_slice($data, 1, 2))[0];


        $availabeStatus = $this->readingFileService->getPath("$this->disk/$this->r_file_path");
        $statusDocType = $this->readingFileService->getPath("$this->disk/$this->w_file_path");

        switch ($typeAction) {
            case 'add':
                $nameStatus = $data['add'];
                $newProps = array_values(array_merge($statusDocType, [$nameStatus]));
                $jsonManage = json_encode($newProps, JSON_PRETTY_PRINT);
                try {
                    $output = Storage::disk('json')->put($this->w_file_path, $jsonManage, 'public'); // wwhy output has always false value
                    return redirect()->back()->with(compact('nameStatus'));
                } catch (\Throwable $th) {
                }
                break;
            case 'remove':
                $nameStatus = [$data['remove']];
                $newStatusDocType = array_diff($statusDocType, $nameStatus);
                $jsonManage = json_encode(array_values($newStatusDocType), JSON_PRETTY_PRINT);

                try {
                    $output = Storage::disk('json')->put($this->w_file_path, $jsonManage, 'public'); // wwhy output has always false value
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
                $jsonManage = json_encode(array_values($docType), JSON_PRETTY_PRINT);
                try {
                    $output = Storage::disk('json')->put($this->w_file_path, $jsonManage, 'public'); // wwhy output has always false value
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
                $jsonManage = json_encode(array_values($docType), JSON_PRETTY_PRINT);
                try {
                    $output = Storage::disk('json')->put($this->w_file_path, $jsonManage, 'public'); // wwhy output has always false value
                    return redirect()->back();
                } catch (\Throwable $th) {
                }
                break;
            default:
                break;
        }
    }
}
