<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\User;
use App\Utils\Support\DateTimeConcern;
use Illuminate\Support\Facades\Blade;

trait TraitSupportEntityShow
{
    public function getTableColumns()
    {
        return [
            [
                "title" => 'Description',
                "dataIndex" => "name_description",
                'width' => 300,
            ],
            [
                "dataIndex" => "response_type",
                'title' => "Response",
                "align" => "center",
                'width' => 600,
            ],
        ];
    }
    private function transformDataSource($dataSource, $entityShtSigs)
    {
        foreach ($dataSource as &$value) {
            $value['response_type'] = $this->createDataSource($value, false);
            $value['name_description'] = $this->createDataSourceDescription($value);
            $value['group_description'] = $value->getGroup->name ?? '';
        }
        if ($entityShtSigs) {
            foreach ($entityShtSigs as &$value) {
                $value['response_type'] = $this->createDataSource($value, true);
                $value['name_description'] = $this->createDataSourceDescription($value);
                $value['group_description'] = 'Third Party Sign-Off';
            }
        } else {
            $entityShtSigs = collect();
        }
        return array_merge($dataSource->toArray(), $entityShtSigs->toArray());
    }
    private function transFormLine($item)
    {
        $controlGroup = $item->getControlGroup->name ?? null;
        $controlRender = $item->getControlType->name ?? 'signature';
        $str = '';
        if (!is_null($controlGroup)) {
            $str = "<table class='text-sm text-left text-gray-500 dark:text-gray-400'>";
            $str .= "<tbody>";
            $str .= "<tr title='Chklst Line ID: {$item->id}' class=' bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" . $this->createStrHtmlGroupRadio($item, $controlGroup) . "</tr>";
            $str .= $this->createStrHtmlCorrectiveAction($item);
            $str .= "</tbody>";
            $str .= "</table>";
        } else {
            switch ($controlRender) {
                case 'signature':
                    $valueSignature = $item->value;
                    $valueSignature = htmlspecialchars($valueSignature);
                    $str = Blade::render(
                        "<div class='flex pb-2 justify-between items-center'>
                            <x-controls.signature.signature2a name='signature{$item->id}' value='$valueSignature' readOnly=1 />                            
                        </div>",
                    );

                    break;
                case 'text':
                    $str = "<p class='font-medium p-2 border'>{$item->value}</p>";
                    break;
                default:
                    # code...
                    break;
            }
        }

        return [$str, $controlRender == 'signature'];
    }

    function removeOnHold(&$array)
    {
        if (in_array($this->type, ['qaqc_insp_chklst_sht', 'qaqc_insp_chklst'])) array_pop($array);
    }

    private function createStrHtmlGroupRadio($item, $controlGroup)
    {
        $arrayControl = explode('|', $controlGroup);
        // dump(array_keys($arrayControl));
        $this->removeOnHold($arrayControl);

        $controlValue = $item->getControlValue->name ?? '';
        $circleIcon = "<i class='fa-thin fa-circle pr-1'></i>";
        $str = "";
        foreach ($arrayControl as $value) {
            if ($controlValue === $value) {
                switch ($value) {
                    case in_array($value, ['Yes', 'Pass']):
                        $checkedIcon = "<i class='fa-solid fa-circle-check pr-1 text-green-700'></i>";
                        $value = "<span class=' text-green-700'>" . $value . "</span>";
                        $valueRender = "<div class='bg-green-300'>" . $checkedIcon . $value . "</div>";
                        break;
                    case in_array($value, ['No', 'Fail']):
                        $checkedIcon = "<i class='fa-solid fa-circle-check pr-1 text-pink-700'></i>";
                        $value = "<span class=' text-pink-700'>" . $value . "</span>";
                        $valueRender = "<div class='bg-pink-300'>" . $checkedIcon . $value . "</div>";
                        break;
                    case 'NA':
                        $checkedIcon = "<i class='fa-solid fa-circle-check pr-1 text-gray-700'></i>";
                        $value = "<span class=' text-gray-700'>" . $value . "</span>";
                        $valueRender = "<div class='bg-gray-300'>" . $checkedIcon . $value . "</div>";
                        break;
                    case 'On Hold':
                        $checkedIcon = "<i class='fa-solid fa-circle-check pr-1 text-orange-700'></i>";
                        $value = "<span class=' text-orange-700'>" . $value . "</span>";
                        $valueRender = "<div class='bg-orange-300'>" . $checkedIcon . $value . "</div>";
                        break;
                    default:
                        break;
                }
                $str .= '<td class="border text-center" style="width:30px">' . $valueRender . '</td>';
            } else {
                $str .=  '<td class="border text-center" style="width:30px">' . $circleIcon . $value . '</td>';
            }
        };
        $longStr =  $str; // $runUpdated;
        return $longStr;
    }
    private function createInspectorAndDatetime($item, $isSignature, $isSignatureLine)
    {
        $inspector = "";
        $dateTime = DateTimeConcern::convertForLoading('picker_date', substr($item->updated_at, 0, 10));
        if ($isSignatureLine || $isSignature) {
            if ($item->value == '') return "";
            $inspId = null;
            if ($isSignatureLine) $inspId = $item->inspector_id;
            if ($isSignature) $inspId = $item->user_id;
            // $inspId = $item->user_id || $item->inspector_id;
            if (is_null($inspId)) return "";
            $renderInspector = Blade::render("<div class='flex justify-end ml-20'><x-renderer.avatar-user uid='$inspId' content='$dateTime' /></div>");
            return $renderInspector;
        } else {
            $value = $item->qaqc_insp_control_value_id ?: $item->hse_insp_control_value_id;
            // dump($value);
            if ($value > 0) { //If it is YES|NO|NA|ONHOLD
                $uid = $item->inspector_id; //?? $item->user_id;
                if ($uid) {
                    $user = User::find($uid);
                    $name = $user ? $user->name : "";
                    $avatar = $user ? $user->getAvatarThumbnailUrl() : "";
                    $avatarStr = $avatar ? "<img src='$avatar' class='w-6 h-6 rounded-full' />" : "";
                    $inspector = $avatarStr . ' ' . $name . " ";
                    return '<span class="flex gap-1">' . $inspector . $dateTime . "</span>";
                }
            }
        }
    }
    private function createStrHtmlAttachment($item)
    {
        if (isset($item->insp_photos) && !$item->insp_photos->isEmpty()) {
            $span = '<span class="" colspan=5 style="width:190px">'  . $this->formatAttachmentRender($item->insp_photos) . '</span>';
            return "<div class='bg-white border rounded dark:bg-gray-800 dark:border-gray-700 p-2 my-1'>" . $span . "</div>";
        }
        return '';
    }

    private function createStrHtmlComment($item)
    {
        if (isset($item->insp_comments) && !$item->insp_comments->isEmpty()) {
            $span = "<span class='bor1der p-0' colspan = 5 style='width:190px'>" . $this->formatCommentRender($item->insp_comments) . "</span>";
            return "<div class='bg-white bor1der-b dark:bg-gray-800 dark:border-gray-700'>" . $span . "</div>";
        }
        return '';
    }
    private function createStrHtmlCorrectiveAction($item)
    {
        if (isset($item->getCorrectiveActions) && !$item->getCorrectiveActions->isEmpty()) {
            $td = "<td class='border p-1' colspan = 5 style='width:190px'>" . $this->formatCorrectiveAction($item->getCorrectiveActions) . "</td>";
            return "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" . $td . "</tr>";
        }
        return '';
    }
    private function formatCorrectiveAction($items)
    {
        $strCenter = "";
        foreach ($items as $key => $correctiveAction) {
            $ownerName = ' Created by: ' . $correctiveAction->getOwner->name0 ?? '';
            $workAreaName = $correctiveAction->getWorkArea->name ?? '';
            $information = $ownerName . ' (' . $workAreaName . ')';
            $status = $correctiveAction->status ?? '';
            $statusHtml = Blade::render("<x-renderer.status>$status</x-renderer.status>");
            $informationHtml = ($key + 1) . '. ' . $correctiveAction->name . '.' . $information . ' ' . $statusHtml;
            $title = $correctiveAction->description . '#' . $correctiveAction->id;
            $href = route('hse_corrective_actions.show', $correctiveAction->id);
            $buttonHtml = Blade::render("<x-renderer.button size='xs' target='_blank' class='m-1 text-left' title='$title' href='$href'>$informationHtml</x-renderer.button>");
            $strCenter .= "$buttonHtml";
        }
        return $strCenter;
    }
    private function formatCommentRender($items)
    {
        $value = $items->toArray();
        $strCenter = Blade::render('<x-print.comment5 :value="$value" />', ['value' => $value]);
        return $strCenter;
    }

    private function formatAttachmentRender($items)
    {
        $strCenter = '';
        $isRenderSimple = $items->every(function ($item) {
            return in_array($item->extension, ['pdf', 'csv', 'zip']);
        });
        if ($isRenderSimple) {
            $strCenter = Blade::render('<x-print.attachment-simple :dataSource="$value"/>', [
                'value' => $items->toArray()
            ]);
        } else {
            $strCenter = Blade::render('<x-renderer.attachment2 openType="_blank" name="attachment" :value="$value" destroyable={{false}} showToBeDeleted={{false}} showUploadFile={{false}} />', [
                'value' => $items->toArray()
            ]);
        }
        return $strCenter;
    }
    private function createDataSourceDescription($value)
    {
        return "<div>
                    <p>{$value->name}</p>
                    <i class='text-sm'>{$value->description}</i>
                </div>";
    }
    private function createDataSource($value, $isSignature)
    {
        // dump($isSignature);
        [$mainTable, $isSignatureLine] = $this->transFormLine($value);
        $inspector = $this->createInspectorAndDatetime($value, $isSignature, $isSignatureLine);
        $attachments = $this->createStrHtmlAttachment($value);
        $comments = $this->createStrHtmlComment($value);
        return "<div class='grid grid-cols-2 gap-2' > "
            . $mainTable
            . $inspector
            . "</div>"
            . "<div>"
            . $attachments
            . $comments
            . "</div>";
    }
}
