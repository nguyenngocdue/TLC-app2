<?php

namespace App\Http\Traits;

trait HasStatus
{
    function getAvailableStatuses()
    {
        return ['finished', 'stopped', 'running'];
    }

    function transitionTo($newStatus)
    {
        if (!in_array($newStatus, $this->getAvailableStatuses())) {
            echo "The new status $newStatus is not available for this Model.";
            return false;
        }

        echo "#" . $this->id . " from " . $this->status . " to -> $newStatus";

        $this->status = $newStatus;
        $this->save();
        return true;
    }
}
