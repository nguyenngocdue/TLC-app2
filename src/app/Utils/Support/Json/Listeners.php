<?php

namespace App\Utils\Support\Json;

use Illuminate\Support\Str;

class Listeners extends JsonGetSet
{
    protected static $filename = "listeners.json";

    public static function getAllOf($type)
    {
        $json = parent::getAllOf($type);
        $result = [];
        foreach ($json as $key => $row) {
            $row['triggers'] = Str::parseArray($row['triggers']);
            $row['listen_to_fields'] = Str::parseArray($row['listen_to_fields']);
            $row['listen_to_attrs'] = Str::parseArray($row['listen_to_attrs']);
            $row['columns_to_set'] = Str::parseArray($row['columns_to_set']);
            $row['attrs_to_compare'] = Str::parseArray($row['attrs_to_compare'] ?? 'id'); //<<CONFIG_MIGRATE

            $row['ajax_form_attributes'] = Str::parseArray($row['ajax_form_attributes'] ?? ""); //<<CONFIG_MIGRATE
            $row['ajax_item_attributes'] = Str::parseArray($row['ajax_item_attributes'] ?? ""); //<<CONFIG_MIGRATE
            $row['ajax_default_values'] = Str::parseArray($row['ajax_default_values'] ?? ""); //<<CONFIG_MIGRATE

            if ($row['listen_action']) $result[$key] = $row;
        }
        return $result;
    }
}
