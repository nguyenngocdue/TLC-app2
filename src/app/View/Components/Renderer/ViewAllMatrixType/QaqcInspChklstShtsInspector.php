<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

class QaqcInspChklstShtsInspector extends QaqcInspChklstShts
{
    protected $metaShowPrint = !true;
    protected $metaShowProgress = !true;

    protected $allowCreation = false;

    function  __construct()
    {
        parent::__construct();
        dump("INSPECTOR");
    }
}
