<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\Json\SuperProps;
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

    private function getValidationRules($oldStatus, $newStatus, $action)
    {
        if ($oldStatus == '') $oldStatus = WorkflowFields::getNewFromDefinitions($this->type);


        $rules = [];
        $sp = $this->superProps;
        // dump($this->type);
        $intermediate = $sp["intermediate"];
        // dump($oldStatus);
        // dump($sp['statuses']);

        //In case comment is a fakeRequest, it wont have next status
        $transitions = isset($sp['statuses'][$oldStatus]) ? $sp['statuses'][$oldStatus]['transitions'] : [];
        //Remove the current transition
        $transitions = array_filter($transitions, fn ($item) => $item != $newStatus);
        // dump($transitions);
        // dump($sp);
        // dump($intermediate);

        $toBeRemovedProps = [];
        foreach ($transitions as $statusName) {
            if (isset($intermediate[$oldStatus][$statusName])) {
                $tmp = $intermediate[$oldStatus][$statusName];
                $toBeRemovedProps = [...$toBeRemovedProps, ...$tmp];
            }
        }
        // dump($toBeRemovedProps);

        $workflows = SuperWorkflows::getFor($this->type)['workflows'];
        if (!isset($workflows[$oldStatus])) return [];
        $visibleProps = $workflows[$oldStatus]['visible'];
        $requiredProps = $workflows[$oldStatus]['required'];
        //In case there are props in the intermediate screen, remove them
        // dump($requiredProps);
        $requiredProps = array_diff($requiredProps, $toBeRemovedProps);


        $listOfTableToIgnoreRequired = $this->getListOfTableToIgnoreRequired($action);

        foreach ($this->superProps['props'] as $prop) {
            if (!in_array($prop['name'], $visibleProps)) continue;
            if (in_array($prop['name'], $listOfTableToIgnoreRequired)) continue;
            //Validations in the Default Value Screen
            if (isset($prop['default-values']['validation'])) {
                $commonValidations = $prop['default-values']['validation'];
                $regexValidations = $prop['default-values']['validation_regex'] ?? "";
                $rules[$prop['column_name']] = explode("|", $commonValidations);
                if ($regexValidations) $rules[$prop['column_name']][] = "regex:" . $regexValidations;
            }
            //Validation in the Required screen
            if (in_array('_' . $prop['column_name'], $requiredProps)) $rules[$prop['column_name']][] = 'required';
        }

        foreach ($rules as &$rule) {
            $rule = array_filter($rule, fn ($i) => $i);
            $rule = array_unique($rule);
            $rule = array_values($rule);
        }
        $rules = array_filter($rules, fn ($i) => !empty($i));
        // dd("getValidationRules", $rules);
        // $this->dump1("getValidationRules", $rules, __LINE__);
        // dump("$oldStatus to $newStatus");
        // dump($rules);
        // dd();
        return $rules;
    }
}
