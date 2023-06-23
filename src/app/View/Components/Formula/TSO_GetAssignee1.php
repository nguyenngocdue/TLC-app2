<?php

namespace App\View\Components\Formula;

use App\Models\User;
use App\Models\User_discipline;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperDefinitions;

class TSO_GetAssignee1
{
    public function __invoke($ownerId)
    {
        if (!$ownerId) {
            $currentUser = CurrentUser::get();
        } else {
            $currentUser = User::findFromCache($ownerId);
        }
        $userDisciplineId = $currentUser->discipline;
        return User_discipline::findOrFail($userDisciplineId)->def_assignee ?? null;
    }
}
