<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

class QaqcInspChklstShtsClient extends QaqcInspChklstShts
{
    protected $allowCreation = false;

    function  __construct()
    {
        parent::__construct();
        dump("CLIENT");
    }
}
