<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\BigThink\Oracy;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\User;

class QaqcInspChklstShtsSignature extends QaqcInspChklstShts
{
    protected $allowCreation = false;
    protected $allowCreationPlaceholder = "<span class='w-12 block'></span>";
    protected $showLegend = false;
    protected $headerTop = 40;

    protected $tableTopCenterControl =  "<div class='p-2 m-1 rounded bg-orange-300'></div>Not yet Requested"
        . "<div class='p-2 m-1 rounded bg-blue-300'></div>Requested"
        . "<div class='p-2 m-1 rounded bg-green-300'></div>Approved"
        . "<div class='p-2 m-1 rounded bg-pink-300'></div>Rejected";

    protected $actionBtnList = [
        'exportSCV' => false,
        'printTemplate' => false,
        'approveMulti' => false,
        'sendManyRequest' => true,
    ];

    public function getMatrixDataSource($xAxis)
    {
        $result = Qaqc_insp_chklst_sht::query()
            ->with('signature_qaqc_chklst_3rd_party')
            ->get();

        Oracy::attach("signature_qaqc_chklst_3rd_party_list()", $result);
        // dump($result[0]);
        return $result;
    }

    private function renderer($nominatedUsers, $signatures, $sheetId)
    {
        $result = collect();
        $signaturesIndexed = $signatures->keyBy('user_id');
        // dump($signaturesIndexed);

        foreach ($nominatedUsers as $nominatedUser) {
            $avatarSrc = $nominatedUser->getAvatarThumbnailUrl();
            $avatar = "<img class='w-10 h-10 rounded-full' src='$avatarSrc' />";

            $text = '';
            $color = 'orange';
            if (isset($signaturesIndexed[$nominatedUser->id])) {
                // $text = "requested";
                $signature = $signaturesIndexed[$nominatedUser->id];
                $text = $signature->signature_decision ?: "requested";
            }

            $name = $nominatedUser->first_name;
            $tooltip = "Request to $name has not been sent";
            $onClick = "";
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
                    $tooltip = "Request sent to $name on " . substr($signature->created_at, 0, 10);
                    break;
                default:
                    $onClick = "sendManyRequest($nominatedUser->id, $sheetId)";
                    break;
            }

            $bg = "bg-$color-300 text-$color-700 capitalize";
            $item = "";
            $item .= "<div id='divFace_{$nominatedUser->id}_{$sheetId}' onclick='$onClick' class='$bg flex w-12 items-center border1 rounded-full gap-1 p-1 mb-0.5 cursor-pointer' title='$tooltip'>";
            $item .= $avatar;
            // $item .= $text;
            $item .= "<i id='divCheck_{$nominatedUser->id}_{$sheetId}' class='hidden fa-duotone fa-check absolute text-green-400 w-10 h-10 text-3xl rounded-full bg-opacity-20 bg-green-500'></i>";
            $item .= "</div>";
            $result->push($item);
        }

        return $result->join("");
        // return count($nominatedUsers) . " " . count($signatures);
    }

    protected function makeStatus($document, $forExcel, $route = null)
    {
        // dd($document);
        if (is_null($route)) {
            $route = route($this->type . ".edit", $document->id);
        }

        $signatures = $document->signature_qaqc_chklst_3rd_party ?: collect();
        $nominatedUserIds = $document->{"signature_qaqc_chklst_3rd_party_list()"} ?: collect();
        if (count($nominatedUserIds) == 0) return (object)[];
        $nominatedUsers = $nominatedUserIds->map(fn ($uid) => User::findFromCache($uid));
        $renderer = $this->renderer($nominatedUsers, $signatures, $document->id);

        $openSheet = "<div class='mt-2'><a href='$route' class='bg-blue-600 text-white rounded p-2'>Open</a></div>";
        // [$bgColor, $textColor] = $this->getBackgroundColorAndTextColor($document);

        return (object)[
            'value' => $renderer . $openSheet,
            // 'cell_href' => $route,
            'cell_class' => "cursor-pointer1 w-10",
        ];
    }
}
