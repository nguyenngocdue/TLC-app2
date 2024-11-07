<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

class QaqcInspChklstShtsCouncil extends QaqcInspChklstShtsInspector
{
    protected $allowCreation = false;

    protected $nominatedFn = "council_member_list";
    protected $getSubProjectsOfUserFn = "getSubProjectsOfCouncilMember";
    protected $getProdRoutingsOfUserFn = "getProdRoutingsOfCouncilMember";
}
