<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\BigThink\Oracy;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\User;

class QaqcInspChklstShtsSignature extends QaqcInspChklstShts
{
    protected $allowCreation = false;

    public function getMatrixDataSource($xAxis)
    {
        $result = Qaqc_insp_chklst_sht::query()
            // ->where('qaqc_insp_tmpl_id', $this->qaqcInspTmpl)
            ->with('signature_qaqc_chklst_3rd_party')
            ->get();

        Oracy::attach("signature_qaqc_chklst_3rd_party_list()", $result);
        // dump($result[0]);
        return $result;
    }

    private function renderer($nominatedUsers, $signatures)
    {
        $result = collect();
        $signaturesIndexed = $signatures->keyBy('user_id');
        // dump($signaturesIndexed);

        foreach ($nominatedUsers as $nominatedUser) {
            $avatarSrc = $nominatedUser->getAvatarThumbnailUrl();
            $avatar = "<img class='w-6 h-6 rounded-full' src='$avatarSrc' />";

            $text = 'new';
            $color = 'orange';
            if (isset($signaturesIndexed[$nominatedUser->id])) {
                // $text = "requested";
                $signature = $signaturesIndexed[$nominatedUser->id];
                $text = $signature->signature_decision ?: "requested";
            }

            $name = $nominatedUser->first_name;
            $tooltip = "Request to $name has not been sent";
            switch ($text) {
                case "approved":
                    $color = "green";
                    $tooltip = $name . " approved with comment: " . $signature->signature_comment;
                    break;
                case "rejected":
                    $color = "pink";
                    $tooltip = $name . " rejected with comment: " . $signature->signature_comment;
                    break;
                case "requested":
                    $color = "blue";
                    $tooltip = "Request sent to $name";
                    break;
            }

            $bg = "bg-$color-300 text-$color-700 font-semibold uppercase";
            $item = "<div class='$bg flex w-32 items-center border1 rounded-full gap-1 p-1 mb-0.5 cursor-help' title='$tooltip'>" . $avatar . $text . "</div>";
            $result->push($item);
        }
        return $result->join("");
        return count($nominatedUsers) . " " . count($signatures);
    }

    protected function makeStatus($document, $forExcel, $route = null)
    {
        // dd($document);
        $signatures = $document->signature_qaqc_chklst_3rd_party ?: collect();
        $nominatedUserIds = $document->{"signature_qaqc_chklst_3rd_party_list()"} ?: collect();
        if (count($nominatedUserIds) == 0) return (object)[];
        $nominatedUsers = $nominatedUserIds->map(fn ($uid) => User::findFromCache($uid));
        return (object)[
            'value' => $this->renderer($nominatedUsers, $signatures),
        ];
    }
}
