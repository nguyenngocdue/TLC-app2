<?php

namespace App\Http\Services;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ManageLinePropService 
{
    public function index($nameManage)
    {
        $patch = storage_path() . "/json/$nameManage/manageline.json";
        $patchTableName = storage_path() . "/json/table/$nameManage/tablename.json";
        $tableNames = json_decode(file_get_contents($patchTableName), true);
        if( !file_exists($patch)){
            $columnNames = [];
            $columnTypes = [];
            $columnTableNames = [];
            foreach($tableNames as $key => $tableName ){
                $columnNameTable = Schema::getColumnListing($tableName);
                foreach ($columnNameTable as $columnName){
                    $type = Schema::getColumnType($tableName, $columnName);
                    array_push($columnTypes, $type);
                    array_push($columnTableNames, $key);
                }
                $columnNames = array_merge($columnNames, $columnNameTable);
            }
        return ['columnTableNames' => $columnTableNames,'columnNames' => $columnNames,'columnTypes' => $columnTypes];
        }
        else{
            $dataManageUser = json_decode(file_get_contents($patch), true);
            $columnTableNames = [];
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
                $columnTableNames[$key] = $data['table_name'];
                $columnNames[$key] = $data['column_name'];
                $columnTypes[$key] = $data['column_type'];
                $columnLabels[$key] = $data['label'];
                $columnControls[$key] = $data['control'];
                $columnColSpans[$key] = $data['col_span'];
                $columnNewLines[$key] = $data['new_line'];
                $colorLines[$key] = $data['type_line'];
            }
            $globalColumnNames = [];
            foreach($tableNames as $key =>$tableName ){
                $columnNameTable = Schema::getColumnListing($tableName);
                foreach($columnNameTable as $columnName){
                    $globalColumnNames[$key.'|'.$columnName] = $columnName;
                }
            }
            $diff1 = array_diff_key($columnNames,$globalColumnNames);
            $diff2 = array_diff_key($globalColumnNames,$columnNames);
            if(empty($diff1) && empty($diff2)){
                return ['columnTableNames'=> $columnTableNames,'names' => $names,'columnNames' => $columnNames,'columnTypes' => $columnTypes
                ,'columnLabels' => $columnLabels,'columnControls' => $columnControls
                ,'columnColSpans' => $columnColSpans,'columnNewLines' => $columnNewLines,'colorLines' => $colorLines];
            }else if (empty($diff1)){
                foreach($diff2 as $key => $value){
                    $keyTableName = explode('|' , $key);
                    $names[$key]=$keyTableName[0].'|'.$value;
                    $columnTableNames[$key]=$keyTableName[0];
                    $columnNames[$key] = $value;
                    $type = Schema::getColumnType($tableNames[$keyTableName[0]], $value);
                    $columnTypes[$key] =$type;
                    $columnLabels[$key] =$value;
                    $columnControls[$key] ="input";
                    $columnColSpans[$key] = "12";
                    $columnNewLines[$key] = "false";
                    $colorLines[$key] = "new";
                }
                return ['columnTableNames'=> $columnTableNames,'names' => $names,'columnNames' => $columnNames,'columnTypes' => $columnTypes
                ,'columnLabels' => $columnLabels,'columnControls' => $columnControls
                ,'columnColSpans' => $columnColSpans,'columnNewLines' => $columnNewLines,'colorLines' => $colorLines];
            }else{
                foreach($diff1 as $key => $value){
                    $colorLines[$key] = "removed";
                }
                return ['columnTableNames'=> $columnTableNames,'names' => $names,'columnNames' => $columnNames,'columnTypes' => $columnTypes
                ,'columnLabels' => $columnLabels,'columnControls' => $columnControls
                ,'columnColSpans' => $columnColSpans,'columnNewLines' => $columnNewLines,'colorLines' => $colorLines];
            }
            return ['columnTableNames'=> $columnTableNames,'names' => $names,'columnNames' => $columnNames,'columnTypes' => $columnTypes
            ,'columnLabels' => $columnLabels,'columnControls' => $columnControls
            ,'columnColSpans' => $columnColSpans,'columnNewLines' => $columnNewLines,'colorLines' => $colorLines];
        }
    }
    public function store($request,$manageName){
        $data = $request->input();
        $maganeUser = [];
        foreach ($data['name'] as $key => $name){
            $array = [];
            $array['table_name']= $data['table_name'][$key];
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
            return Storage::disk('json')->put($manageName.'/manageline.json',$jsonManageUser,'public');
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function destroy($name , $nameManage){
        $patch = storage_path() . "/json/$nameManage/manageline.json";
        $dataManageUser = json_decode(file_get_contents($patch), true);
        unset($dataManageUser[$name]);
        try {
            return Storage::disk('json')->put($nameManage.'/manageline.json',json_encode($dataManageUser),'public');
            Toastr::success('Save file json successfully', 'Save file json');
        } catch (\Throwable $th) {
            return false;
        }
    }

}