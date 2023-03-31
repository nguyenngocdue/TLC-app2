<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\Json\SuperWorkflows;
use App\Utils\Support\WorkflowFields;
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

    private function getListOfTableToIgnoreRequired($action)
    {
        $result = [];
        if ($action === 'store') {
            $sp = $this->superProps;
            foreach ($sp['tables'] as $functions) {
                foreach ($functions as $function) {
                    $result[] = '_' . $function;
                }
            }
        }
        return $result;
    }

    private function getValidationRules($newStatus, $action)
    {
        if ($newStatus == '') $newStatus = WorkflowFields::getNewFromDefinitions($this->type);
        $rules = [];
        $workflows = SuperWorkflows::getFor($this->type)['workflows'];
        if (!isset($workflows[$newStatus])) return [];
        $visibleProps = $workflows[$newStatus]['visible'];
        $requiredProps = $workflows[$newStatus]['required'];

        $listOfTableToIgnoreRequired = $this->getListOfTableToIgnoreRequired($action);

        foreach ($this->superProps['props'] as $prop) {
            if (!in_array($prop['name'], $visibleProps)) continue;
            if (in_array($prop['name'], $listOfTableToIgnoreRequired)) continue;
            if (isset($prop['default-values']['validation'])) {
                $commonValidations = $prop['default-values']['validation'];
                $regexValidations = $prop['default-values']['validation_regex'] ?? "";
                $rules[$prop['column_name']] = explode("|", $commonValidations);
                if ($regexValidations) $rules[$prop['column_name']][] = "regex:" . $regexValidations;
                if (in_array('_' . $prop['column_name'], $requiredProps)) $rules[$prop['column_name']][] = 'required';
            }
        }

        foreach ($rules as &$rule) {
            $rule = array_filter($rule, fn ($i) => $i);
            $rule = array_unique($rule);
            $rule = array_values($rule);
        }
        $rules = array_filter($rules, fn ($i) => !empty($i));
        // dd("getValidationRules", $rules);
        // $this->dump1("getValidationRules", $rules, __LINE__);
        // dump($rules);
        // dd();
        return $rules;
    }
}
