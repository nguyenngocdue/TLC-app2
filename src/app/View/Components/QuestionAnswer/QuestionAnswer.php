<?php

namespace App\View\Components\QuestionAnswer;

use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class QuestionAnswer extends Component
{
    function __construct(
        private $item = [],
    ) {
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
        ];

        $item = $this->item;
        $questionType = $item['question_type_id'] ?? null;
        $staticAnswer = $item['static_answer'] ?? "FFF";
        $dynamicAnswer = $item['dynamic_answer'] ?? "GGG";
        $control = $controlIds[$questionType];

        Log::info($staticAnswer);
        Log::info($dynamicAnswer);

        return view(
            'components.question-answer.question-answer',
            [
                'item' => $item,
                'control' => $control,
                'staticAnswer' => $staticAnswer,
                'dynamicAnswer' => $dynamicAnswer,
            ]
        );
    }
}
