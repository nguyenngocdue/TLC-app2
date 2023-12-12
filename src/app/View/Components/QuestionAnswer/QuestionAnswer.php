<?php

namespace App\View\Components\QuestionAnswer;

use App\Models\Department;
use App\Models\Department_skill_group;
use App\Models\User;
use App\Models\User_discipline;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class QuestionAnswer extends Component
{
    private $MY_DEPT_USERS = 386;
    private $MY_DEPT_USERS_EXCLUDE_ME = 387;
    private $MY_DEPT_SUB_USERS = 395;
    private $MY_DEPT_TECH_SKILLS = 391;
    private $MY_RELATED_DEPTS = 394;

    function __construct(
        private $item = [],
        private $debug = !false,
    ) {
    }

    function getMemberOfTeam($department)
    {
        $members = $department
            ->getMembers()
            ->whereNot('resigned', 1)
            ->whereNot('show_on_beta', 1)
            ->whereNot('time_keeping_type', 1) //1:TSW
            ->orderBy('name0');
        return $members;
    }

    function getDynamicContent($id)
    {
        $cu = CurrentUser::get();
        $department = $cu->getUserDepartment;

        switch ($id) {
            case $this->MY_DEPT_USERS:
                $members = $this->getMemberOfTeam($department)
                    ->get();
                $members1 = $members->map(fn ($u) => ['id' => $u->id, 'name' => $u->name, 'group' => 'no_group', 'avatar' => $u->getAvatarThumbnailUrl(),]);
                return $members1;
            case $this->MY_DEPT_USERS_EXCLUDE_ME:
                $members = $this->getMemberOfTeam($department)
                    ->whereNot('id', $cu->id)
                    ->get();
                $members = $members->map(fn ($u) => ['id' => $u->id, 'name' => $u->name, 'group' => 'no_group', 'avatar' => $u->getAvatarThumbnailUrl(),]);
                return $members;
            case $this->MY_DEPT_SUB_USERS:
                $r = User_discipline::query()->where('def_assignee', /*772*/ $cu->id)->get()->pluck('id')->toArray();
                $members = User::query()
                    ->whereIn('discipline', $r)
                    ->whereNot('resigned', 1)
                    ->whereNot('show_on_beta', 1)
                    ->whereNot('time_keeping_type', 1) //1:TSW
                    ->orderBy('name0')
                    ->with('getUserDiscipline')
                    ->get();
                // dd($members);
                $members = $members->map(fn ($u) => ['id' => $u->id, 'name' => $u->name, 'group' => $u->getUserDiscipline->name, 'avatar' => $u->getAvatarThumbnailUrl(),]);
                // dd($members);
                return $members;
            case $this->MY_DEPT_TECH_SKILLS:
                $skills = $department->getSkillsOfDepartment();
                $allGroupIds = $skills->pluck('department_skill_group_id');
                $allGroups = Department_skill_group::whereIn('id', $allGroupIds)->get()->pluck('name', 'id');
                // dump($allGroups);
                $skills = $skills->map(fn ($i) => ['id' => $i->id, 'name' => $i->name, 'group' => $allGroups[$i->department_skill_group_id]]);
                // $skills = $skills->pluck('name', 'id')->toArray();
                // dd($skills);
                return $skills;
            case $this->MY_RELATED_DEPTS:
                $relatedMatrix = config("departments.related");
                $result = [];
                foreach ($relatedMatrix as $indexX => $list) {
                    foreach ($list as $indexY) {
                        $result[$indexX][$indexY] = 1;
                        $result[$indexY][$indexX] = 1;
                    }
                }
                $result = array_keys($result[$department->id]);
                // dump($result);
                $result = Department::whereIn('id', $result)
                    ->orderBy('name')
                    ->get()
                    ->map(fn ($i) => ['id' => $i->id, 'name' => $i->name, 'group' => 'no_group']);
                // ->pluck('name', 'id')
                // ->toArray();
                return $result;
            default:
                if ($id) dump("Unknown how to get dynamic answer from [$id]");
                return collect([]);
        }
    }

    function makeUpDynamicData($dynamicCollection)
    {
        $result = $dynamicCollection->groupBy('group');
        return $result;
    }

    function render()
    {
        $controlIds = [
            377 => 'text',
            378 => 'textarea',
            379 => 'radio-static',
            380 => 'radio-dynamic',
            381 => 'checkbox-static',
            382 => 'checkbox-dynamic',
            383 => 'ranking-static',
            388 => 'ranking-dynamic',
            389 => 'checkbox-stanamic',
            390 => 'radio-stanamic',
            392 => 'radio-dynanamic',
            393 => 'checkbox-dynanamic',
        ];

        $item = $this->item;
        $questionType = $item['question_type_id'] ?? null;

        $staticAnswer = explode("|", $item['static_answer'] ?? '');
        $dynamicAnswerRows = $this->makeUpDynamicData($this->getDynamicContent($item['dynamic_answer_rows'] ?? ''));
        $dynamicAnswerRowGroups = $dynamicAnswerRows->keys();
        // dump($dynamicAnswerRowGroups);
        $dynamicAnswerCols = $this->makeUpDynamicData($this->getDynamicContent($item['dynamic_answer_cols'] ?? ''));
        $control = $controlIds[$questionType];
        $renderAsRow = $item['render_as_rows'];
        // Log::info($staticAnswer);
        // Log::info($dynamicAnswer);
        // Log::info($renderAsRow);
        // dump($dynamicAnswerRows);
        // dd();
        return view(
            'components.question-answer.question-answer',
            [
                'item' => $item,
                'control' => $control,
                'staticAnswer' => $staticAnswer,
                'dynamicAnswerRows' => $dynamicAnswerRows,
                'dynamicAnswerRowGroups' => $dynamicAnswerRowGroups,
                'dynamicAnswerCols' => $dynamicAnswerCols,
                'renderAsRows' => $renderAsRow,
                'debug' => $this->debug,
            ]
        );
    }
}
