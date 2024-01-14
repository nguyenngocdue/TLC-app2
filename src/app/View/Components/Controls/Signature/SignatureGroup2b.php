<?php

namespace App\View\Components\Controls\Signature;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class SignatureGroup2b extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $category,
        private $signableId,
        private $type,
        private $item,
        private $readOnly = false,
        private $debug = !true,
        private $title = "Sign Off",
        // private $signOffOracy = "getMonitors1",

    ) {
        // dump($item);
        // dump($category);
        // dump($signableId, $type);
    }

    private function mergeUserAndSignature($nominatedList, $signatureList)
    {
        // dump($nominatedList);
        // dump($signatureList);
        $sA = [];
        foreach ($signatureList as $sign) $sA[$sign->user_id] = $sign;
        foreach ($nominatedList as &$user) {
            if (isset($sA[$user->id])) {
                $user->attached_signature = $sA[$user->id];
            }
        }
        return $nominatedList;
    }

    public function render()
    {
        if (!isset($this->item->{$this->category})) return "<i class='text-xs font-light' title='Category: $this->category'>Please create this document before signing off.</i>";
        $signatureList = $this->item->{$this->category};
        // $nominatedList = $this->item->{$this->signOffOracy}();
        $nominatedList = [];
        $cuid = CurrentUser::id();
        $isExternalInspector = CurrentUser::get()->isExternalInspector();

        echo "Signature group 2 b";

        $signatures = $this->mergeUserAndSignature($nominatedList, $signatureList);
        $params = [
            'category' => $this->category,
            'signatures' => $signatures,

            'debug' => $this->debug,
            'input_or_hidden' => $this->debug ? "text" : "hidden",

            'cuid' => $cuid,
            'isExternalInspector' => $isExternalInspector,

            'tableName' => $this->type,
            'signableId' => $this->signableId,
            'readOnly' => $this->readOnly,
        ];
        return view('components.controls.signature.signature-group2b', $params);
    }
}
