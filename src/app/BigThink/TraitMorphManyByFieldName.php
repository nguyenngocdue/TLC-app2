<?php

namespace App\BigThink;

use Database\Seeders\FieldSeeder;

trait TraitMorphManyByFieldName
{
    function morphManyByFieldName($morphManyRelation, $fieldName, $column = 'field_id')
    {
        $fieldId = FieldSeeder::getIdFromFieldName($fieldName);
        // error_log("FieldID of $fieldName is $fieldId");
        $morphManyRelation->getQuery()->where($column, $fieldId);
        return $morphManyRelation;
    }
}
