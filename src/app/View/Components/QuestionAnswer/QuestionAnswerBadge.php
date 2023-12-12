<?php

namespace App\View\Components\QuestionAnswer;

use Illuminate\View\Component;

class QuestionAnswerBadge extends Component
{
    function __construct(
        private $id,
        private $selected = '',
        private $validation = 450,
    ) {
    }

    static function getValidationType()
    {
        return [
            450 => '',
            451 => 'Required',
            452 => 'Min 3',
        ];
    }

    function render()
    {
        $validationType = static::getValidationType()[$this->validation];

        return view(
            'components.question-answer.question-answer-badge',
            [
                'id' => $this->id,
                'selected' => $this->selected,
                'validation' => $this->validation,
                'validationType' => $validationType,
            ]
        );
    }
}
