<?php

namespace App\View\Components\Controls\Signature;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class SignatureGroup2a extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $category,
        private $signableId,
        private $item,
        private $type,
        private $readOnly = false,
        private $debug = !true,
        private $title = "Sign Off",
        private $signOffOracy = null,

    ) {
        if (is_null($signOffOracy)) $this->signOffOracy = $category . "_list";
        // dump($this->signOffOracy);
        // dump($category);
        // dump($item);
        // dump($signableType, $signableId);
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
        $nominatedList = $this->item->{$this->signOffOracy}();
        // dump($nominatedList);
        $signatureList = $this->item->{$this->category}()->with('getUser')->get();
        // dump($signatureList);
        $cuid = CurrentUser::id();
        $isExternalInspector = CurrentUser::get()->isExternalInspector();

        $signatures = $this->mergeUserAndSignature($nominatedList, $signatureList);
        $all = ($nominatedList->pluck('email', 'id'));
        // dump($all);
        $signed = ($signatureList->pluck('getUser.email', 'user_id'));
        // dump($signed);
        $needToRequest = $all->filter(fn ($email) => !$signed->contains($email));
        // dump($needToRequest);
        $alreadySigned = $all->filter(fn ($email) => $signed->contains($email));
        // dump($alreadySigned);
        $needToRecall = $signed->filter(fn ($email) => (!$all->contains($email)) && $email);
        // dump($needToRecall);

        $params = [
            'category' => $this->category,
            'signatures' => $signatures,
            'nominatedList' => $nominatedList,
            'needToRequest' => $needToRequest,
            'alreadySigned' => $alreadySigned,
            'needToRecall' => $needToRecall,

            'debug' => $this->debug,
            'input_or_hidden' => $this->debug ? "text" : "hidden",

            'cuid' => $cuid,
            'isExternalInspector' => $isExternalInspector,

            'tableName' => $this->type,
            'signableId' => $this->signableId,
            'readOnly' => $this->readOnly,
        ];
        return view('components.controls.signature.signature-group2a', $params);
    }
}
