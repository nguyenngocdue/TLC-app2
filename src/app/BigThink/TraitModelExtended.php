<?php

namespace App\BigThink;

trait TraitModelExtended
{


    //This static function is useful when a model is needed to get table name but data also is empty
    public static function getTableName()
    {
        return (new static())->getTable();
    }

    public function next()
    {
        $result = $this->where('id', '>', $this->id)->orderBy('id', 'asc')->limit(1)->first();
        return $result;
    }

    public function previous()
    {
        $result = $this->where('id', '<', $this->id)->orderBy('id', 'desc')->limit(1)->first();
        return $result;
    }
}
