<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Models\User;
use App\Utils\Support\Tree\BuildTree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class MyOrgChartController extends Controller
{
    use TraitViewAllFunctions;
    public function getType()
    {
        return "dashboard";
    }
    public function index(Request $request)
    {
        $tree = BuildTree::getTree();
        $results = [];
        $showOptions = $this->getUserSettingsViewOrgChart();
        $this->x($tree,$results,$this->getOptionsRenderByUserSetting($showOptions));
        usort($results,function($a,$b){
            return strcmp($a['name'],$b['name']);
        });
        return view(
            'welcome-canh',
            ['dataSource'=> $results,'showOptions'=>$showOptions]
        );
    }
    private function x($tree,&$results,$options){
        foreach ($tree as $value) {
            if(isset($value->children)){
                $this->x($value->children,$results,$options);
            }
            if(App::isProduction()) {
                if($value->show_on_beta == 0){
                    $a = $this->convertDataSource($value,$options);
                    if($a)$results[] = $a;
                }
            }
            else{ 
                $a = $this->convertDataSource($value,$options);
                if($a)$results[] = $a;
            }
        }
    }
    private function convertDataSource($value,$options){
        if(in_array($value->resigned,$options['resigned']) 
            && in_array($value->time_keeping_type,$options['time_keeping_type'])){
            $user = User::findFromCache($value->id);
            $positionRendered = $user->position_rendered;
            $avatar = $user->getAvatarThumbnailUrl() ?? '';
            return [
                'key' => $value->id,
                'name' => $value->name,
                'parent' => $value->parent_id,
                'avatar' => $avatar,
                'fill' => $this->getFillColor($value),
                'title' => $positionRendered,
            ];
        }
    }
    private function getFillColor($item){
        return $item->resigned == 1 ? '#d1d5db' : ($item->time_keeping_type == 3 ? "#fed7aa" : "#ffffff");
    }
    private function getOptionsRenderByUserSetting($showOptions){
        $results = [
            'resigned' => [0],
            'time_keeping_type' => [2,3],
        ];
        foreach ($showOptions as $key => $value) {
                switch ($key) {
                    case 'resigned':
                        if($value == 'true') $results['resigned'] = [0,1];
                        break;
                    case 'time_keeping_type':
                        if($value == 'true') $results['time_keeping_type'] = [1,2,3];
                            break;
                    default:
                        break;
            }
        }
        return $results;
    }
}
