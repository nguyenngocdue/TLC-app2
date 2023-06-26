<?php

namespace App\View\Components\Controls\Signature;

use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\Json\SuperProps;
use Database\Seeders\FieldSeeder;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class SignatureGroup2 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $category,
        private $signableType,
        private $item,
        private $readOnly = false,
        private $debug = false,
        private $title = "Sign Off",
    ) {
        // dump($item);
        // dump($category);
    }

    private function alreadySigned($signatures, $user)
    {
        $uid = $user->id;
        foreach ($signatures as $signature) {
            // dump($signature->owner_id);
            if ($signature->owner_id == $uid) {
                return true;
            }
        }
        return false;
    }

    function getRemainingList($designatedApprovers, $signatures)
    {
        $signed = $signatures->map(fn ($s) => $s['owner_id']);
        // dump($signed);
        $signedArr =  $signed->toArray();
        $notYetSigned = $designatedApprovers->filter(fn ($item) => !in_array($item->id, $signedArr));
        $result = [];
        foreach ($notYetSigned as $person) {
            $result[$person->id]['short'] = $person->name . " - " . $person->email;
            $person->avatar = $person->getAvatarThumbnailUrl();
            $result[$person->id]['full'] = $person;
            $result[$person->id]['valid_email'] =  str_contains($person->email, '@');
        }

        return $result;
    }

    function isRequestedToSign($designatedApprovers, $currentUser)
    {
        // dump($designatedApprovers);
        foreach ($designatedApprovers as $approver) {
            if ($approver->id == $currentUser->id) return true;
        }
        return false;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (!isset($this->item->{$this->category})) return "<i class='text-xs font-light' title='Category: $this->category'>Please create this document before signing off.</i>";
        $signatures = $this->item->{$this->category};

        $approverFn = SuperProps::getFor($this->signableType)['props']["_" . $this->category]['relationships']['renderer_edit_param'];

        if (!$approverFn) return "<i class='text-xs font-light'>Please insert approverFn (like getMonitors1) into Manage Relationship.renderer_edit_param</i>";
        $labelOfApproverFn = SuperProps::getFor($this->signableType)['props']["_" . $approverFn . "()"]['label'];
        $designatedApprovers = $this->item->{$approverFn}();

        $selectedStr = "[" . join(",", $designatedApprovers->pluck('id')->toArray()) . ']';
        $currentUser = CurrentUser::get();
        foreach ($signatures as &$signature) {
            $user = User::findFromCache($signature['owner_id']);
            $signature['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' =>  $user['email'],
                'avatar' => $user->getAvatarThumbnailUrl(),
                'full_name' => $user['full_name'],
                'position_rendered' => $user['position_rendered'],
                'timestamp' => DateTimeConcern::convertForLoading("picker_datetime", $signature['created_at']),
            ];
            $signature['updatable'] = ($user['id'] == $currentUser->id);
            // $signature['category'] = $this->category;
        }
        $currentUserObject = [
            'id' => $currentUser->id,
            'name' => $currentUser->name,
            'email' => $currentUser->email,
            'avatar' =>  $currentUser->getAvatarThumbnailUrl(),
            'full_name' => $currentUser['full_name'],
            'position_rendered' => $currentUser['position_rendered'],
            'timestamp' => null,
        ];
        // dd($currentUser);
        $remainingList = $this->getRemainingList($designatedApprovers, $signatures);
        $people = [];
        $remindList = [];
        $index = 0;
        foreach ($remainingList as $person) {
            if ($person['valid_email']) {
                $people[] = (++$index) . ". " . $person['short'];
                $remindList[] = $person['full']->id;
            }
        }
        // dd($people);
        // dump($signatures);
        $alreadySigned = $this->alreadySigned($signatures, $currentUser);
        $isRequestedToSign0 = $this->isRequestedToSign($designatedApprovers, $currentUser);
        $params = [
            'signatures' => $signatures,
            'category' => FieldSeeder::getIdFromFieldName($this->category),
            'type' => $this->signableType,
            'signableType' => Str::modelPathFrom($this->signableType),
            'signableId' => $this->item->id,
            'selected' => $selectedStr,
            'currentUser' => $currentUserObject,
            'input_or_hidden' => $this->debug ? "text" : "hidden",
            'debug' => $this->debug,
            'alreadySigned' => $alreadySigned,
            'isRequestedToSign0' => $isRequestedToSign0,
            'designatedApprovers' => $designatedApprovers,

            'remainingList' => $remainingList,
            'remindList' => $remindList,
            'requestButtonTitle' => "Send a friendly request email to:\n" . join("\n", $people) . "\n(If any email address is invalid, those users will be ignored)",
            'approverFn' => $approverFn,
            'title' => $this->title,
            'labelOfApproverFn' => $labelOfApproverFn,
        ];
        // dump($params);
        return view('components.controls.signature.signature-group2', $params);
    }
}
