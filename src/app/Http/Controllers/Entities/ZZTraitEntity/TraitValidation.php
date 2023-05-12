<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\Json\SuperDefinitions;
use App\Utils\Support\Json\SuperWorkflows;
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

    private function getListOfTableNameToIgnoreRequired($action)
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

    private function getPropsInIntermediateScreen($oldStatus, $newStatus)
    {
        $sp = $this->superProps;
        $intermediate = $sp["intermediate"];
        //In case comment is a fakeRequest, it wont have next status
        $transitions = isset($sp['statuses'][$oldStatus]) ? $sp['statuses'][$oldStatus]['transitions'] : [];
        //Remove the current transition
        $transitions = array_filter($transitions, fn ($item) => $item != $newStatus);
        // dump($transitions);
        // dump($sp);
        // dump($intermediate);

        $result = [];
        foreach ($transitions as $statusName) {
            if (isset($intermediate[$oldStatus][$statusName])) {
                $tmp = $intermediate[$oldStatus][$statusName];
                $result = [...$result, ...$tmp];
            }
        }
        // dump($result);
        return $result;
    }

    private function getValidationInDefaultValuesScreen($prop)
    {
        $result = [];
        if (isset($prop['default-values']['validation'])) {
            $commonValidations = $prop['default-values']['validation'];
            if (strlen($commonValidations)) $result = explode("|", $commonValidations);

            $regexValidations = $prop['default-values']['validation_regex'] ?? "";
            if ($regexValidations) $result[] = "regex:" . $regexValidations;
        }
        return $result;
    }

    private function getVisibleProps($hasStatusColumn, $workflows, $oldStatus)
    {
        $result = [];
        if ($hasStatusColumn) {
            $result = $workflows[$oldStatus]['visible'];
        } else {
            $props =  $this->superProps['props'];
            $array = array_filter($props, fn ($prop) => $prop["hidden_edit"] !== 'true');
            $result = array_keys($array);
        }
        // dump($result);
        return $result;
    }

    private function getRules($hasStatusColumn, $workflows, $oldStatus)
    {
        $result = [];
        if ($hasStatusColumn) {
            $requiredProps = $workflows[$oldStatus]['required'];
            foreach ($requiredProps as $propName) {
                $propObject = $this->superProps['props'][$propName];
                $otherValidationRules = $this->getValidationInDefaultValuesScreen($propObject);
                $result[$propName] = [...$otherValidationRules, 'required'];
            }
            foreach ($result as &$prop) $prop = array_unique($prop);
        } else {
            foreach ($this->superProps['props'] as $prop) {
                $rules = $this->getValidationInDefaultValuesScreen($prop);
                if (!empty($rules)) $result[$prop['name']] = $rules;
            }
        }
        // dump($result);
        return $result;
    }

    private function getValidationRules($oldStatus, $newStatus, $action)
    {
        if ($oldStatus == '') $oldStatus = SuperDefinitions::getNewOf($this->type);
        // if ($oldStatus == '') $oldStatus = WorkflowFields::getNewFromDefinitions($this->type);

        //When user click "Save" button, no intermediate prop will need to be considered
        $propsInIntermediateScreen = $this->getPropsInIntermediateScreen($oldStatus, $newStatus);
        $listOfTableNameToIgnoreRequired = $this->getListOfTableNameToIgnoreRequired($action);

        $workflows = SuperWorkflows::getFor($this->type)['workflows'];
        $hasStatusColumn = isset($workflows[$oldStatus]);
        $visibleProps = $this->getVisibleProps($hasStatusColumn, $workflows, $oldStatus);
        $requiredProps = $this->getRules($hasStatusColumn, $workflows, $oldStatus);

        // dump($propsInIntermediateScreen);
        // dump($listOfTableNameToIgnoreRequired);
        // dump($visibleProps);
        // dump($requiredProps);

        $result = [];
        foreach ($requiredProps as $propName => $rule) {
            if (!in_array($propName, $visibleProps)) continue;
            if (in_array($propName, $propsInIntermediateScreen)) continue;
            if (in_array($propName, $listOfTableNameToIgnoreRequired)) continue;
            $column_name  = substr($propName, 1);
            $result[$column_name] = $rule;
        }
        // dump($result);
        return $result;
    }
}
