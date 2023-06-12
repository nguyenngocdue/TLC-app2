<?php

namespace App\View\Components\Controls\InspChklst;

use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class SignOff extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $signatures,
        private $type,
        private $item,
        private $debug = false,
    ) {
        //
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

    function getRemindList($designatedApprovers, $signatures)
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
        $designatedApprovers = $this->item->getMonitors1();
        $selectedStr = "[" . join(",", $designatedApprovers->pluck('id')->toArray()) . ']';
        $signatures = $this->signatures;
        // $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
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
        $remindList = $this->getRemindList($designatedApprovers, $signatures);
        $people = [];
        $index = 0;
        foreach ($remindList as $person) {
            $people[] = (++$index) . ". " . $person['short'];
        }
        // dd($people);

        $alreadySigned = $this->alreadySigned($signatures, $currentUser);
        $isRequestedToSign0 = $this->isRequestedToSign($designatedApprovers, $currentUser);
        return view('components.controls.insp-chklst.sign-off', [
            'signatures' => $this->signatures,
            'type' => $this->type,
            'signableId' => $this->item->id,
            'selected' => $selectedStr,
            'currentUser' => $currentUserObject,
            'input_or_hidden' => $this->debug ? "text" : "hidden",
            'debug' => $this->debug,
            'alreadySigned' => $alreadySigned,
            'isRequestedToSign0' => $isRequestedToSign0,

            'remindList' => $remindList,
            // 'people' => count($remindList) . " " . Str::plural("person"),
            'title' => "Send a friendly email to:\n" . join("\n", $people),
        ]);
    }
}
