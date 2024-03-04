<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\ClassList;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;

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
    public function showAll(Request $request){
        if(CurrentUser::isAdmin()){
            $valueOptionPrint = $this->getValueOptionPrint();
            $layout = $this->getLayoutPrint($valueOptionPrint, 'props');
            $class = ClassList::DROPDOWN;
            $type = $this->type;
            $typePlural =Str::plural($this->type);
            $modelPath = $this->typeModel;
            $topTitle =CurrentRoute::getTitleOf($this->type);
            $dataSource = $this->typeModel::all()->where('status','active');
            foreach ($dataSource as $item) {
                $id = $item->id;
                $params =  [
                    'type' => $type,
                    'typePlural' => $typePlural,
                    'item' => $item,
                    'id' => $id,
                    'classListOptionPrint' => $class,
                    'valueOptionPrint' => $valueOptionPrint,
                    'layout' => $layout,
                    'modelPath' => $modelPath,
                    'trashed' => false,
                    'topTitle' => $topTitle,
                    'numberOfEmptyLines' => 5,
                ];
                $name = $type.'_'.$id.'.pdf';
                //$html = view('dashboards.pages.entity-show-props',$params)->render(); 
                // dd($html); return pdf()->html($html)->download($name);
                return pdf()->view('dashboards.pages.entity-show-props',$params)->download($name);
            }
            // Pdf::view('dashboards.pages.entity-show-props',$params)->save('example2.pdf');
            // Browsershot::url('https://google.com')->save('example4.pdf');
        }
        
    }
}
