<?php
namespace App\Http\Controllers\Workflow;
use App\Utils\Support\Json\Props;
class LibProfileFields extends AbstractLib
{
    protected static $key = "profile_fields";
    public static function getAll()
    {
        $userProps = (Props::getAllOf('users'));
        $result = [];
        $json = parent::getAll();
        // $apps = Entities::getAll();
        foreach ($userProps as $userProp) {
            // $tableName = $app->getTable();
            $userName =  $userProp['name'];
            if (isset($json[$userName])) {
                $line = $json[$userName];
            } else {
                $line = [
                    'name' => $userProp['name'],
                    'row_color' => 'green',
                ];
            }
            $result[] = $line;
        }
        return $result;
    }
    // public static function getFor($key)
    // {
    //     $data = array_filter(static::getAll(), fn ($i) => isset($i[$key]));
    //     $data = array_map(fn ($i) => $i['name'], $data);
    //     // dump($data);
    //     return $data;
    // }
}