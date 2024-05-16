<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

class QaqcInspChklstShtsCouncil extends QaqcInspChklstShts
{
    protected $allowCreation = false;

    function  __construct()
    {
        parent::__construct();
        dump("COUNCIL");
    }
}
