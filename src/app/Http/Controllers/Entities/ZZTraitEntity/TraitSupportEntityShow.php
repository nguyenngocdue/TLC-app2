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
                "align" => "center",
                'width' => 600,
            ],
        ];
    }
    private function transformDataSource($dataSource, $entityShtSigs)
    {
        foreach ($dataSource as &$value) {
            $value['response_type'] = $this->createDataSourceTableRun($value);
            $value['name_description'] = $this->createDataSourceDescription($value);
            $value['group_description'] = $value->getGroup->name ?? '';
        }
        if ($entityShtSigs) {
            foreach ($entityShtSigs as &$value) {
                $value['response_type'] = $this->createDataSourceTableRun($value);
                $value['name_description'] = $this->createDataSourceDescription($value);
                $value['group_description'] = 'Third Party  Sign Off';
            }
        } else {
            $entityShtSigs = collect();
        }
        return array_merge($dataSource->toArray(), $entityShtSigs->toArray());
    }
    private function transFormLine($item)
    {
        $controlGroup = $item->getControlGroup->name ?? null;
        $str = '';
        if (!is_null($controlGroup)) {
            $str .= "<tr title='Chklst Line ID: {$item->id}' class=' bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" . $this->createStrHtmlGroupRadio($item, $controlGroup) . "</tr>";
            $str .=  $this->createStrHtmlCorrectiveAction($item);
            $str .= $this->createStrHtmlAttachment($item);
            $str .=  $this->createStrHtmlComment($item);
        } else {
            $controlRender = $item->getControlType->name ?? 'signature';
            switch ($controlRender) {
                case 'signature':
                    $valueSignature = $item->value;
                    // dd($item->insp_comments);
                    $inspectorId = isset($item->getControlType->name) ? $item->inspector_id : $item->owner_id;
                    $updatedAt = DateTimeConcern::convertForLoading('picker_datetime', $item->updated_at);
                    $renderInspector = $inspectorId ? "<div class='text-right mr-5'>
                    <x-renderer.avatar-user uid='$inspectorId'></x-renderer.avatar-user>
                    @if($inspectorId)
                        <p>$updatedAt</p>
                    @endif
                    </div>" : "";
                    // $inspectorName = null;
                    // if ($inspectorId) {
                    //     $inspectorName = User::findFromCache($inspectorId)->full_name;
                    // }

                    $valueSignature = htmlspecialchars($valueSignature);

                    $str = Blade::render(
                        "<div class='flex pb-2 justify-between items-center'>
                            <x-controls.signature.signature2a name='signature' value='$valueSignature' readOnly=1 />
                            $renderInspector
                        </div>",
                    );

                    $str .= $this->createStrHtmlAttachment($item);
                    $str .=  $this->createStrHtmlComment($item);
                    break;
                case 'text':
                    $str = "<p class='font-medium'>{$item->value}</p>";
                    break;
                default:
                    # code...
                    break;
            }
        }
        return $str;
    }
    private function createStrHtmlGroupRadio($item, $controlGroup)
    {
        $arrayControl = explode('|', $controlGroup);
        if (($this->type) == 'qaqc_insp_chklst_sht') array_pop($arrayControl);
        $controlValue = $item->getControlValue->name ?? '';
        $circleIcon = "<i class='fa-thin fa-circle px-2'></i>";
        $str = "";
        foreach ($arrayControl as $value) {
            if ($controlValue === $value) {
                switch ($value) {
                    case in_array($value, ['Yes', 'Pass']):
                        $checkedIcon = "<i class='fa-solid fa-circle-check px-2 text-green-700'></i>";
                        $value = "<span class=' text-green-700'>" . $value . "</span>";
                        $valueRender = "<div class='bg-green-300'>" . $checkedIcon . $value . "</div>";
                        break;
                    case in_array($value, ['No', 'Fail']):
                        $checkedIcon = "<i class='fa-solid fa-circle-check px-2 text-pink-700'></i>";
                        $value = "<span class=' text-pink-700'>" . $value . "</span>";
                        $valueRender = "<div class='bg-pink-300'>" . $checkedIcon . $value . "</div>";
                        break;
                    case 'NA':
                        $checkedIcon = "<i class='fa-solid fa-circle-check px-2 text-gray-700'></i>";
                        $value = "<span class=' text-gray-700'>" . $value . "</span>";
                        $valueRender = "<div class='bg-gray-300'>" . $checkedIcon . $value . "</div>";
                        break;
                    case 'On Hold':
                        $checkedIcon = "<i class='fa-solid fa-circle-check px-2 text-orange-700'></i>";
                        $value = "<span class=' text-orange-700'>" . $value . "</span>";
                        $valueRender = "<div class='bg-orange-300'>" . $checkedIcon . $value . "</div>";
                        break;
                    default:
                        break;
                }
                $str .= '<td class="border" style="width:50px">' . $valueRender . '</td>';
            } else {
                $str .=  '<td class="border" style="width:50px">' . $circleIcon . $value . '</td>';
            }
        };
        $runUpdated = $this->createStrHtmlDateTime($item);
        // $runDesc =  env('APP_ENV')  === 'local' ? '<td class="border" style="width:10px">' . $item->description . ":" . "</td>" : "";
        // $lineId =  env('APP_ENV')  === 'local' ? '<td class="border" style="width:10px">' . 'line_id:' .  $item->id . '</td>' : "";
        $longStr = /*$runDesc . $lineId .*/  $str . $runUpdated;
        return $longStr;
    }
    private function createStrHtmlDateTime($item)
    {
        $runUpdated = '<td class="border pl-2" style="width:50px" >' . DateTimeConcern::convertForLoading('picker_datetime', $item->updated_at) . "</td>";
        return $runUpdated;
    }
    private function createStrHtmlAttachment($item)
    {
        if (isset($item->insp_photos) && !$item->insp_photos->isEmpty()) {
            $td = '<td class="b1order" colspan=5 style="width:190px">'  . $this->formatAttachmentRender($item->insp_photos) . '</td>';
            return "<tr  class=' bg-white bord1er-b dark:bg-gray-800 dark:border-gray-700'>" . $td . "</tr>";
        }
        return '';
    }

    private function createStrHtmlComment($item)
    {
        if (isset($item->insp_comments) && !$item->insp_comments->isEmpty()) {
            $td = "<td class='bor1der p-0' colspan = 5 style='width:190px'>" . $this->formatCommentRender($item->insp_comments) . "</td>";
            return "<tr class=' bg-white bor1der-b dark:bg-gray-800 dark:border-gray-700'>" . $td . "</tr>";
        }
        return '';
    }
    private function createStrHtmlCorrectiveAction($item)
    {
        if (isset($item->getCorrectiveActions) && !$item->getCorrectiveActions->isEmpty()) {
            $td = "<td class='border p-1' colspan = 5 style='width:190px'>" . $this->formatCorrectiveAction($item->getCorrectiveActions) . "</td>";
            return "<tr class=' bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" . $td . "</tr>";
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
        // dd($strCenter);
        // foreach ($items as  $comment) {
        //     $ownerComment = $comment->getOwner ?? '';
        //     $updatedAt = DateTimeConcern::convertForLoading('picker_datetime', $comment->updated_at);
        //     $ownerRender = Blade::render("<x-renderer.avatar-user verticalLayout='true' uid='$ownerComment->id'></x-renderer.avatar-user>");
        //     <x-print.comment5 :relationships="$relationships" :value="$value" />
        //     $strCenter .= "<div class='mt-2 border-b'>
        //                         $ownerRender
        //                         <p class='text-xs font-normal text-center mt-1'>$updatedAt</p>
        //                     </div>
        //                     <div class='col-span-3 border-b'>
        //                         <p class='px-1'>{$comment->content}</p>
        //                     </div>
        //                     ";
        // }
        // $strHead = "<div class='flex flex-col w-full' >
        //             <div class='grid grid-cols-4 lg:gap-3 md:gap-2 sm:gap-1 '>";
        // $strTail = "</div></div>";
        // return $strHead . $strCenter . $strTail;
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
            $strCenter = Blade::render('<x-renderer.attachment2 name="attachment" :value="$value" destroyable={{false}} showToBeDeleted={{false}} showUploadFile={{false}} />', [
                'value' => $items->toArray()
            ]);
        }
        return $strCenter;
        // $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
        // $strCenter = "";
        // foreach ($items as  $attachment) {
        //     $urlThumbnail = $path . $attachment->url_thumbnail;
        //     $urlMedia = $path . $attachment->url_media;
        //     $fileName = $attachment->filename;
        //     $strCenter .= "
        //                     <div class='border-gray-300 relative h-full flex mx-1 flex-col items-center p-1 border rounded-lg  group/item overflow-hidden bg-inherit'>
        //                         <img class='' src='$urlThumbnail' alt='$fileName' />
        //                         <div class='invisible flex justify-center hover:bg-[#00000080] group-hover/item:visible before:absolute before:-inset-1  before:bg-[#00000080]'>
        //                                 <a title='$fileName' href='$urlMedia' target='_blank' class='hover:underline text-white hover:text-blue-500 px-2 absolute top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] text-lg text-center w-full'>
        //                                     <span class='text-sm'><i class='fa-sharp fa-solid fa-eye text-2xl '></i></span>
        //                                 </a>
        //                         </div>
        //                     </div>";
        // };
        // $strHead = "<div class='flex flex-col container mx-auto w-full' >
        //             <div class='grid grid-cols-5 lg:gap-3 md:gap-2 sm:gap-1 '>";
        // $strTail = "</div></div>";
        // return $strHead . $strCenter . $strTail;
    }
    private function createDataSourceTableRun($value)
    {
        $html = "<table class = 'w-full text-sm text-left text-gray-500 dark:text-gray-400'>" . "<tbody>" . $this->transFormLine($value) . "</tbody>" . "</table>";
        return $html;
    }
    private function createDataSourceDescription($value)
    {
        return "<div>
                    <p>{$value->name}</p>
                    <i class='text-sm'>{$value->description}</i>
                </div>";
    }
}
