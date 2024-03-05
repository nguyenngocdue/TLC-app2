<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\ClassList;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Unit;
use \ZipArchive;
use function Spatie\LaravelPdf\Support\pdf;

trait TraitViewAllPrint
{
    public function print(Request $request)
    {
        $dataSource = $request->except(['_token', '_method', '_entity', 'action', 'per_page']);
        if (empty($dataSource)) abort(404, "No document was selected to print.");

        $typePlural = Str::plural($this->type);
        $valueOptionPrint = $this->getValueOptionPrint();
        $params =  [
            'type' => $this->type,
            'typePlural' => $typePlural,
            'dataSource' => $dataSource,
            'classListOptionPrint' => ClassList::DROPDOWN,
            'valueOptionPrint' => $valueOptionPrint,
            'layout' => $this->getLayoutPrint($valueOptionPrint, 'props'),
            'modelPath' => $this->typeModel,
            'trashed' => false,
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'numberOfEmptyLines' => 5,
        ];
        return view('dashboards.pages.entity-show-template-print', $params);
    }
    public function printAll(Request $request)
    {
        if (CurrentUser::isAdmin() || CurrentUser::isHrManager()) {
            $valueOptionPrint = $this->getValueOptionPrint();
            $layout = $this->getLayoutPrint($valueOptionPrint, 'props');
            $class = ClassList::DROPDOWN;
            $type = $this->type;
            $typePlural = Str::plural($this->type);
            $modelPath = $this->typeModel;
            $topTitle = CurrentRoute::getTitleOf($this->type);
            $dataSource = $this->typeModel::all()->where('status', 'active');
            if (!sizeof($dataSource) > 0) return;
            $zip = new ZipArchive;
            $zipFileName = $typePlural . '.zip';
            $fileTmp = [];
            if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
                foreach ($dataSource as $item) {
                    $params =  [
                        'type' => $type,
                        'typePlural' => $typePlural,
                        'item' => $item,
                        'id' => $item->id,
                        'classListOptionPrint' => $class,
                        'valueOptionPrint' => $valueOptionPrint,
                        'layout' => $layout,
                        'modelPath' => $modelPath,
                        'trashed' => false,
                        'topTitle' => $topTitle,
                        'numberOfEmptyLines' => 5,
                    ];
                    $name = $item->name . '.pdf';
                    pdf()->view('dashboards.pages.entity-show-props', $params)
                        ->withBrowsershot(function (Browsershot $browsershot) {
                            $browsershot->scale(0.8);
                        })
                        ->format('a4')
                        ->margins(50, 75, 50, 75, Unit::Pixel)
                        ->save($name);
                    $file = public_path($name);
                    $fileTmp[] = $file;
                    $zip->addFile($file, basename($file));
                }
                $zip->close();
                // Delete the file after adding it to the zip
                foreach ($fileTmp as $value) {
                    if (file_exists($value))
                        unlink($value);
                }
                return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
            } else {
                return "Failed to create the zip file.";
            }
        }
    }
}
