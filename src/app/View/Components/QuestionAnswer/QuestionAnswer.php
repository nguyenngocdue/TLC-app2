<?php

namespace App\View\Components\QuestionAnswer;

use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class QuestionAnswer extends Component
{
    function __construct(
        private $item = [],
    ) {
    }

    function getDynamicContent($id)
    {
        $MY_DEPT_USERS = 386;
        $MY_DEPT_USERS_EXCLUDE_ME = 387;
        $MY_DEPT_TECH_SKILLS = 391;

        $cu = CurrentUser::get();
        $department = $cu->getUserDepartment;

        switch ($id) {
            case $MY_DEPT_USERS:
                $members = $department
                    ->getMembers()
                    ->whereNot('resigned', 1)
                    ->whereNot('show_on_beta', 1)
                    ->orderBy('name0')
                    ->get();
                return $members->map(fn ($u) => ['id' => $u->id, 'name' => $u->name,])->pluck('name', 'id')->toArray();
            case $MY_DEPT_USERS_EXCLUDE_ME:
                $members = $department
                    ->getMembers()
                    ->whereNot('resigned', 1)
                    ->whereNot('show_on_beta', 1)
                    ->whereNot('id', $cu->id)
                    ->orderBy('name0')
                    ->get();
                return $members->map(fn ($u) => ['id' => $u->id, 'name' => $u->name,])->pluck('name', 'id')->toArray();
            case $MY_DEPT_TECH_SKILLS:
                $skills = $department->getTechnicalSkillsOfDepartment();
                $skills = $skills->pluck('name', 'id')->toArray();
                return $skills;
            default:
                if ($id) dump("Unknown how to get dynamic answer from [$id]");
                return collect([]);
        }
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
        ];

        $item = $this->item;
        $questionType = $item['question_type_id'] ?? null;

        $staticAnswer = explode("|", $item['static_answer'] ?? '');
        $dynamicAnswer = $this->getDynamicContent($item['dynamic_answer'] ?? '');
        $control = $controlIds[$questionType];
        $renderAsRow = $item['render_as_rows'];
        // Log::info($staticAnswer);
        // Log::info($dynamicAnswer);
        // Log::info($renderAsRow);

        return view(
            'components.question-answer.question-answer',
            [
                'item' => $item,
                'control' => $control,
                'staticAnswer' => $staticAnswer,
                'dynamicAnswer' => $dynamicAnswer,
                'renderAsRows' => $renderAsRow,
            ]
        );
    }
}
