<?php

namespace App\View\Components\QuestionAnswer;

use Illuminate\View\Component;

class QuestionAnswer extends Component
{
    function __construct(
        private $item = [],
    ) {
    }

    function render()
    {
        return view(
            'components.question-answer.question-answer',
            [
                'item' => $this->item,
            ]
        );
    }
}
