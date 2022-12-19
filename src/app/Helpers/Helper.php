<?php

namespace App\Helpers;

use App\Utils\Support\Relationships;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Helper
{
    private static function getDataFromPathModel($modelPath, $byFilters = [])
    {
        $model = App::make($modelPath);
        $nameless = ($model->nameless);

        $insTableSource = new $modelPath();
        $tableName = $insTableSource->getTable();
        $table = DB::table($tableName);
        if (count($byFilters)) {
            return [$tableName =>  $table->where($byFilters)->get()];
        }
        $dataSource =  $nameless ? $table->get() : $table->orderBy('name')->get();
        return [$tableName => $dataSource];
    }

    public static function getDataSource($modelPath, $colName, $type)
    {
        $instance = new $modelPath;
        $eloquentParam = $instance->eloquentParams;


        $relationship = Relationships::getAllOf($type);
        // dump($colName);
        $elementRel = array_values(array_filter($relationship, fn ($item) => $item['control_name'] === $colName))[0] ?? [];

        $keyNameEloquent = "";
        foreach ($eloquentParam as $key => $value) {
            // dd($colName, $value);
            if (in_array($colName, $value)) {
                $keyNameEloquent = $key;
                break;
            }
        }
        $byFilters = [];
        if (isset($elementRel['filter_columns']) && $elementRel['filter_columns'] && $elementRel['filter_values']) {
            $byFilters = [$elementRel['filter_columns'] => $elementRel['filter_values']];
        }

        if ($keyNameEloquent === "") {
            // dd($elementRel);
            $pathTableSource =  $eloquentParam[$elementRel['control_name']][1] ?? "11111";
            return Helper::getDataFromPathModel($pathTableSource, $byFilters);
            $pathTableSource =  $eloquentParam[$elementRel['control_name']][1] ?? "";
            return Helper::getDataFromPathModel($pathTableSource, $byFilters) ?? [];
        }
        $pathTableSource = $eloquentParam[$keyNameEloquent][1];
        return Helper::getDataFromPathModel($pathTableSource, $byFilters) ?? [];
    }


    public static function customMessageValidation($message, $colName, $labelName)
    {
        $newMessage = str_replace($colName, ("<strong>" . $labelName . "</strong>"), $message);
        return $newMessage;
    }

    public static function customizeSlugData($file, $tableName, $media)
    {
        $dataSource = json_decode(DB::table($tableName)->select('filename', 'extension')->get(), true);
        $data = array_map(fn ($item) => array_values($item)[0], $dataSource) ?? [];
        $dataExtension = array_map(fn ($item) => array_values($item)[1], $dataSource) ?? [];

        // dd($dataSource, $data, $dataExtension);
        $fileName =  $file->getClientOriginalName();
        $extensionFile = $file->getClientOriginalExtension();

        $mediaNames = array_map(fn ($item) => $item['filename'], $media);

        $separateFileName = array_keys(Helper::getName_NumericalOrderMedia($fileName))[0];
        $isValueInData = Helper::isValueInData($data, $dataExtension, $separateFileName, $extensionFile);

        if (in_array($fileName, $mediaNames)  || $isValueInData) {
            $maxValOnDB =  Helper::getMaxNumberMediaName($data, $fileName, $extensionFile);
            $max_ValOnTemp =  Helper::getMaxNumberMediaName($mediaNames, $fileName, $extensionFile);
            $fileNameNumberMax = array_values($maxValOnDB)[0] > array_values($max_ValOnTemp)[0] ? $maxValOnDB : $max_ValOnTemp;

            $fileName =  array_keys($fileNameNumberMax)[0] . '-' . array_values($fileNameNumberMax)[0] + 1 . '.' . $extensionFile;
            return $fileName;
        }
        return $fileName;
    }

    private static function indexCharacterInString($strSearch, $str, $ofs = 0)
    {
        while (($pos = strpos($str, $strSearch, $ofs)) !== false) {
            $ofs = $pos + 1;
        }
        return $ofs - 1;
    }

    private static function getName_NumericalOrderMedia($fileName)
    {
        $idx1 = Helper::indexCharacterInString('-', $fileName);
        $idx2 = Helper::indexCharacterInString('.', $fileName);
        $strName = (string)substr($fileName, 0, $idx1);
        if ($idx1 >= 0) {
            $number = substr($fileName, $idx1 + 1, $idx2 - $idx1 - 1);
            if (!is_numeric($number)) {
                $strName = $strName . substr($fileName, $idx1, $idx2 - $idx1);
                return [$strName => 0];
            }
            return [$strName => $number];
        }
        $strName = (string)substr($fileName, 0, $idx2);

        return [$strName => 0];
    }

    private static function getMaxNumberMediaName($data, $fileName, $extensionFile = '')
    {
        $idx = Helper::indexCharacterInString('.', $fileName);
        $fname = substr($fileName, 0,  $idx);

        $similarNames = [];
        foreach ($data as $value) {
            $separateName = Helper::getName_NumericalOrderMedia($value);
            $_idx = Helper::indexCharacterInString('.', $value);
            $valExtension = substr($value, $_idx + 1, strlen($value) - $_idx);
            if ((string)array_keys($separateName)[0] === $fname && $valExtension === $extensionFile) {
                $similarNames[] = $value;
            }
        }
        $fileName = Helper::getMaxName($similarNames, $fileName);

        $fileName_number_max =  Helper::getName_NumericalOrderMedia($fileName);
        return $fileName_number_max;
    }

    private static function isValueInData($data1, $data2, $strSearch, $extensionFile)
    {
        foreach ($data1 as $key => $value) {
            if (str_contains($value, $strSearch) && str_contains($data2[$key], $extensionFile)) {
                return true;
            }
        }
        return false;
    }

    // public static function removeItemsByKeysArray($originalArray, $keysArray)
    // {
    //     foreach ($keysArray as $value) {
    //         unset($originalArray[$value]);
    //     }
    //     return $originalArray;
    // }

    public static function getColNamesByControlAndColumnType($props, $control, $columnType)
    {
        $props = array_filter($props, fn ($prop) => $prop['control'] === $control && $prop['column_type'] === $columnType);
        $colNameByControls = array_values(array_map(fn ($item) => $item['column_name'], $props));
        return $colNameByControls;
    }

    // public static function getValColNamesValueNotEmpty($props, $controlName)
    // {
    //     $props = array_filter($props, fn ($prop) => $prop[$controlName] != '');
    //     $colNameByControls = array_values(array_map(fn ($item) => $item[$controlName], $props));
    //     return $colNameByControls;
    // }

    // public static function getColNamesValueNotEmpty($props, $controlName)
    // {
    //     $props = array_filter($props, fn ($prop) => $prop[$controlName] != '');
    //     $colNameByControls = array_values(array_map(fn ($item) => $item, $props));
    //     return $colNameByControls;
    // }

    private static function getMaxName($objNames, $fileName)
    {
        $_fileName = $fileName;
        foreach ($objNames as $value) {
            $maxName = 0;
            $separateName = Helper::getName_NumericalOrderMedia($value);
            if ((int)array_values($separateName)[0] > $maxName) {
                $maxName = (int)array_values($separateName)[0];
                $_fileName = $value;
            }
        }
        return $_fileName;
    }

    private static function getTheSameNamesInDB($dataDBbySlug, $name)
    {
        // dd($dataDBbySlug, $name);
        $index = Helper::indexCharacterInString('-', $name);
        $tailName = substr($name, $index + 1, strlen($name) - $index);
        $nameInput = is_numeric($tailName) ? substr($name, 0, $index) : $name;
        // dd($nameInput, $tailName);

        $arrayNames = [];
        foreach ($dataDBbySlug as $valName) {
            if (str_contains($valName, $nameInput)) {
                $nameExtend = substr($valName, strlen($nameInput) + 1, strlen($valName) - strlen($nameInput));
                if ($nameExtend === '') {
                    $arrayNames[] = $valName;
                }
                if (is_numeric($nameExtend)) {
                    $arrayNames[] = $valName;
                }
            }
        }
        // dd($arrayNames);
        return $arrayNames;
    }

    private static function getMaxNumberName($similarNames, $nameInput)
    {
        $maxNumber = 0;
        foreach ($similarNames as $name) {
            $index = Helper::indexCharacterInString('-', $name);
            $temp = (int)substr($name,  $index >= 0 ? $index + 1 : strlen($nameInput) + 1, strlen($name) - $index);
            if (is_numeric($temp) &&  $temp > $maxNumber) {
                $maxNumber = $temp;
            }
        }
        return $maxNumber;
    }

    public static function slugNameToBeSaved($nameInput, $dataDBbyName)
    {
        if (!in_array($nameInput, $dataDBbyName)) return $nameInput;

        $similarNames =  Helper::getTheSameNamesInDB($dataDBbyName, $nameInput);
        $max =  Helper::getMaxNumberName($similarNames, $nameInput) + 1;
        $result = "$nameInput-$max";
        return $result;
    }

    // public static function getValueByIdAndTableFromDB($arrayIdsTableNames)
    // {
    //     $arrayValues = [];
    //     foreach ($arrayIdsTableNames as $tableName => $idItem) {
    //         $data = DB::table($tableName)->where('id', $idItem)->select('name')->get();
    //         $arrayValues[] = implode(array_column(json_decode($data, true), 'name'));
    //     }
    //     return $arrayValues;
    // }

    public static function getDataDbByName($tableName, $keyCol = '', $valueCol = '')
    {
        $dataDB = DB::table($tableName)->get();
        $result = array_column($dataDB->toArray(), $valueCol, $keyCol);
        return $result;
    }

    public static function getDataModelByName($modelName, $id,  $keyCol = '', $valueCol = '')
    {
        $modelPath = "App\\Models\\" . Str::singular($modelName);
        $result = $modelPath::find($id)->pluck($keyCol, $valueCol)->toArray();
        return $result;
    }
    public static function getAndChangeKeyItemsContainString($dataInput, $strSearch)
    {
        $newItems = [];
        foreach ($dataInput as $key => $value) {
            if (str_contains($key, $strSearch)) {
                $newKey = str_replace($strSearch, '', $key);
                $newItems[$newKey] = $value;
            }
        }
        return $newItems;
    }


    public  static function getItemModel($type, $id = '')
    {
        $modelPath = "App\\Models\\" . Str::singular($type);
        if (!$id) return  App::make($modelPath);
        return $modelPath::find($id)->get();
    }
    public  static function getItemModelByFn($type, $id = '', $fnName = '')
    {
        $modelPath = "App\\Models\\" . Str::singular($type);
        return $modelPath::find($id)->{$fnName}()->get();
    }
}
