<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

class QaqcInspChklstShtsShippingAgent extends QaqcInspChklstShtsInspector
{
    protected $allowCreation = false;

    protected $nominatedFn = "shipping_agent_list";
    protected $getSubProjectsOfUserFn = "getSubProjectsOfShippingAgent";
    protected $getProdRoutingsOfUserFn = "getProdRoutingsOfShippingAgent";
}
