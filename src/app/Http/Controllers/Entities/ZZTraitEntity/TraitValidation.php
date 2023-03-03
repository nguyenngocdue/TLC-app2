<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Illuminate\Support\MessageBag;

trait TraitValidation
{
    private function createTableValidator($e, $request)
    {
        $validator = $e->validator;
        $idForScroll = $request['idForScroll'];

        $validationErrors = $validator->errors()->getMessages();
        $subErrors = [];
        foreach ($validationErrors as $key => $msg) {
            $subErrors[$key] = $msg;
            unset($validationErrors[$key]);
        }

        $newValidation = new MessageBag($validationErrors);
        // dd($subErrors);
        foreach ($subErrors as $key => $msg) {
            $newValidation->getMessageBag()->add($idForScroll, $msg[0]);
        }
        return $newValidation;
    }

    private function getValidationRules()
    {
        $rules = [];
        foreach ($this->superProps['props'] as $prop) {
            if (isset($prop['default-values']['validation'])) {
                $commonValidations = $prop['default-values']['validation'];
                $regexValidations = $prop['default-values']['validation_regex'] ?? "";
                $rules[$prop['column_name']] = explode("|", $commonValidations);
                if ($regexValidations) $rules[$prop['column_name']][] = "regex:" . $regexValidations;
            }
        }
        $rules = array_filter($rules, fn ($i) => $i);
        // dd("getValidationRules", $rules);
        // $this->dump1("getValidationRules", $rules, __LINE__);
        return $rules;
    }
}
