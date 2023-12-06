<?php

namespace App\View\Components\QuestionAnswer;

use Illuminate\View\Component;

class QuestionAnswer extends Component
{
    function __construct(
        private $dataSource = [],
        private $index = 0,
    ) {
    }

    function render()
    {
        return view(
            'components.question-answer.question-answer',
            [
                'dataSource' => $this->dataSource,
                'index' => $this->index,
            ]
        );
    }
}
