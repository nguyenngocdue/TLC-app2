<?php

namespace App\View\Components\Formula;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Models\User;
use App\Models\User_discipline;
use App\Utils\Support\CurrentUser;

class TSO_GetAssignee1
{
    use TraitViewAllFunctions;
    private $type;
    public function __invoke($type = null,$ownerId = null)
    {
        $this->type = $type;
        if (!$ownerId) {
            [, $filterViewAllCalendar] = $this->getUserSettingsViewAllCalendar();
            $ownerId = isset($filterViewAllCalendar['owner_id']) ? $filterViewAllCalendar['owner_id'][0] : CurrentUser::id();
        }
        $currentUser = User::findFromCache($ownerId);
        $userDisciplineId = $currentUser->discipline;
        return User_discipline::findOrFail($userDisciplineId)->def_assignee ?? null;
    }
}
