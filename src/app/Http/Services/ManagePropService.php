<?php

namespace App\Http\Services;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ManagePropService 
{
    public function index($nameManage)
    {
        $tableName = $nameManage.'s';
        $patch = storage_path() . "/json/$nameManage/manage.json";
        if( !file_exists($patch)){
            $columnNames = Schema::getColumnListing($tableName);
            $columnTypes = [];
            foreach ($columnNames as $columnName){
                $type = Schema::getColumnType($tableName, $columnName);
                array_push($columnTypes, $type);
            }
            return ['columnNames' => $columnNames,'columnTypes' => $columnTypes];
        }
        else{
            $dataManageUser = json_decode(file_get_contents($patch), true);
            $names = [];
            $columnNames = [];
            $columnTypes = [];
            $columnLabels = [];
            $columnControls = [];
            $columnColSpans = [];
            $columnNewLines = [];
            $colorLines = [];
            foreach ($dataManageUser as $key => $data){
                $names[$key] =$key;
                $columnNames[$key] = $data['column_name'];
                $columnTypes[$key] = $data['column_type'];
                $columnLabels[$key] = $data['label'];
                $columnControls[$key] = $data['control'];
                $columnColSpans[$key] = $data['col_span'];
                $columnNewLines[$key] = $data['new_line'];
                $colorLines[$key] = $data['type_line'];
            }
            $diff1 = array_diff($columnNames,Schema::getColumnListing('users'));
            $diff2 = array_diff(Schema::getColumnListing('users'),$columnNames);
            if(empty($diff1) && empty($diff2)){
                return ['names' => $names,'columnNames' => $columnNames,'columnTypes' => $columnTypes
                ,'columnLabels' => $columnLabels,'columnControls' => $columnControls
                ,'columnColSpans' => $columnColSpans,'columnNewLines' => $columnNewLines,'colorLines' => $colorLines];
            }else if (empty($diff1)){
                foreach($diff2 as $value){
                    $names['_'.$value]='_'.$value;
                    $columnNames['_'.$value] = $value;
                    $type = Schema::getColumnType('users', $value);
                    $columnTypes['_'.$value] =$type;
                    $columnLabels['_'.$value] =$value;
                    $columnControls['_'.$value] ="input";
                    $columnColSpans['_'.$value] = "12";
                    $columnNewLines['_'.$value] = "false";
                    $colorLines['_'.$value] = "new";
                }
                return ['names' => $names,'columnNames' => $columnNames,'columnTypes' => $columnTypes
                ,'columnLabels' => $columnLabels,'columnControls' => $columnControls
                ,'columnColSpans' => $columnColSpans,'columnNewLines' => $columnNewLines,'colorLines' => $colorLines];
            }else{
                foreach($diff1 as $value){
                    $colorLines['_'.$value] = "removed";
                }
                return ['names' => $names,'columnNames' => $columnNames,'columnTypes' => $columnTypes
                ,'columnLabels' => $columnLabels,'columnControls' => $columnControls
                ,'columnColSpans' => $columnColSpans,'columnNewLines' => $columnNewLines,'colorLines' => $colorLines];
            }
        }
    }
    public function store($request,$manageName){
        $data = $request->input();
        $maganeUser = [];
        foreach ($data['name'] as $key => $name){
            $array = [];
            $array['column_name']= $data['column_name'][$key];
            $array['column_type']= $data['column_type'][$key];
            $array['label']= $data['label'][$key];
            $array['control']= $data['control'][$key];
            $array['col_span']= $data['col_span'][$key];
            $array['new_line']= $data['new_line'][$key];
            $array['type_line']= "default";
            $maganeUser[$name] = $array;
        }
        $jsonManageUser = json_encode($maganeUser);
        try {
            dd($manageName.'/manage.json');
            return Storage::disk('json')->put($manageName.'/manage.json',$jsonManageUser,'public');
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function destroy($name , $nameManage){
        $patch = storage_path() . "/json/$nameManage/manage.json";
        $dataManageUser = json_decode(file_get_contents($patch), true);
        unset($dataManageUser[$name]);
        try {
            return Storage::disk('json')->put($nameManage.'/manage.json',json_encode($dataManageUser),'public');
            Toastr::success('Save file json successfully', 'Save file json');
        } catch (\Throwable $th) {
            return false;
        }
    }

}