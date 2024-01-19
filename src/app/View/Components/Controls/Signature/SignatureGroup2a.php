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
        // dump($readOnly);
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
        $cuid = CurrentUser::id();
        $isExternalInspector = CurrentUser::get()->isExternalInspector();
        if ($isExternalInspector) {
            $nominatedList = $this->item->{$this->signOffOracy}()->pluck('id');
            if (!$nominatedList->contains(CurrentUser::id())) {
                return "<x-feedback.result type='warning' title='Permission Denied' message='You are not permitted to view this check sheet.<br/>If you believe this is a mistake, please contact our admin.' />";
            }
        }

        $nominatedList = $this->item->{$this->signOffOracy}();
        // dump($nominatedList);
        $signatureList = $this->item->{$this->category}()->with('getUser')->get();
        // dump($signatureList);
        $signatures = $this->mergeUserAndSignature($nominatedList, $signatureList);
        // dump($signatures);
        $all = ($nominatedList->pluck('email', 'id'));
        // dump($all);
        $signed = ($signatureList->pluck('getUser.email', 'user_id'));
        // dump($signed);
        $needToRequest = $all->filter(fn ($email) => !$signed->contains($email));
        // dump($needToRequest);

        $notYetSigned = $signatureList->filter(fn ($signature) => is_null($signature->value))->pluck('getUser.email', 'user_id');
        // dump($notYetSigned);
        $notYetSignedSignatures = $notYetSigned->map(fn ($i, $uid) => $signatureList->where('user_id', $uid)->pluck('id')->toArray()[0]);
        // dump($notYetSignedSignatures);
        // $alreadyRequested = $all->filter(fn ($email) => $signed->contains($email));
        // dump($alreadyRequested);
        // $alreadyRequestedSignatures = $alreadyRequested->map(fn ($i, $uid) => $signatureList->where('user_id', $uid)->pluck('id')->toArray()[0]);
        // dump($alreadyRequestedSignatures);

        $needToRecall = $signed->filter(fn ($email) => (!$all->contains($email)) && $email);
        // dump($needToRecall);
        $needToRecallSignatures = $needToRecall->map(fn ($i, $uid) => $signatureList->where('user_id', $uid)->pluck('id')->toArray()[0]);
        // dump($needToRecallSignatures);

        $allowed = \App\Utils\Support\Json\SuperWorkflows::isAllowed($this->item->status, $this->type);
        // echo $allowed ? "ALLOWED" : "403";

        $params = [
            'category' => $this->category,
            'signatures' => $signatures,
            'nominatedList' => $nominatedList,
            'needToRequest' => $needToRequest,

            'notYetSigned' => $notYetSigned,
            'notYetSignedSignatures' => $notYetSignedSignatures,
            // 'alreadyRequested' => $alreadyRequested,
            // 'alreadyRequestedSignatures' => $alreadyRequestedSignatures,

            'needToRecall' => $needToRecall,
            'needToRecallSignatures' => $needToRecallSignatures,

            'isInNominatedList' => $nominatedList->contains('id', $cuid),

            'debug' => $this->debug,
            'input_or_hidden' => $this->debug ? "text" : "hidden",

            'cuid' => $cuid,
            'isExternalInspector' => $isExternalInspector,

            'tableName' => $this->type,
            'signableId' => $this->signableId,
            'readOnly' => $this->readOnly || !$allowed,
        ];



        return view('components.controls.signature.signature-group2a', $params);
    }
}
