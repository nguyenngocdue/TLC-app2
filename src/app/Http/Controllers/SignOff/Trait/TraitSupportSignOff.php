<?php

namespace App\Http\Controllers\SignOff\Trait;

use App\Models\Qaqc_insp_chklst_sht;
use App\Models\User;
use App\Utils\Support\DateTimeConcern;
use Illuminate\Support\Facades\Blade;

trait TraitSupportSignOff
{
    private function getDataSource($id)
    {
        $chklstSht = Qaqc_insp_chklst_sht::findOrFail($id);
        $runLines = $chklstSht->getLines;
        return [$runLines, $chklstSht];
    }
    public function getTableColumns()
    {
        return [
            [
                "title" => 'Description',
                "dataIndex" => "description",
                'width' => 300,
            ],
            [
                "dataIndex" => "response_type",
                "align" => "center",
                'width' => 600,
            ],
        ];
    }
    private function transformDataSource($dataSource)
    {
        foreach ($dataSource as &$value) {
            $value['response_type'] = $this->createDataSourceTableRun($value);
            $value['group_description'] = $value->getGroup->description ?? '';
        }
        return $dataSource->toArray();
    }
    private function transFormLine($value)
    {
        $controlGroup = $value->getControlGroup->name ?? null;
        $str = '';
        if (!is_null($controlGroup)) {
            $str .= "<tr title='Chklst Line ID: {$value->id}' class=' bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" . $this->createStrHtmlGroupRadio($value) . "</tr>";
            $str .= $this->createStrHtmlAttachment($value);
            $str .=  $this->createStrHtmlComment($value);
        } else {
            $valueSignature = $value->value;
            $inspectorId = $value->inspector_id;
            $inspectorName = null;
            if ($inspectorId) {
                $inspectorName = User::findOrFail($inspectorId)->full_name;
            }
            $updatedAt = DateTimeConcern::convertForLoading('picker_datetime', $value->updated_at);
            $str = Blade::render(
                "<x-controls.signature2 name='signature' value='$valueSignature'/>
                </div>
                <div class='text-right mr-10 mt-1'>
                @if('$inspectorName')
                    <p class='font-medium'>$inspectorName</p>
                    <p>$updatedAt</p>
                @endif 
                ",
            );
        }
        return $str;
    }
    private function createStrHtmlGroupRadio($item)
    {
        $controlGroup = $item->getControlGroup->name ?? '';
        $arrayControl = explode('|', $controlGroup);
        array_pop($arrayControl);
        $controlValue = $item->getControlValue->name ?? '';
        $circleIcon = "<i class='fa-thin fa-circle px-2'></i>";
        $checkedIcon = "<i class='fa-solid fa-circle-check px-2'></i>";
        $str = "";
        foreach ($arrayControl as $value) {
            if ($controlValue === $value) {
                $str .= '<td class="border" style="width:50px">' . $checkedIcon . $value . '</td>';
            } else {
                $str .=  '<td class="border" style="width:50px">' . $circleIcon . $value . '</td>';
            }
        };
        $runUpdated = $this->createStrHtmlDateTime($item);
        // $runDesc =  env('APP_ENV')  === 'local' ? '<td class="border" style="width:10px">' . $item->description . ":" . "</td>" : "";
        // $lineId =  env('APP_ENV')  === 'local' ? '<td class="border" style="width:10px">' . 'line_id:' .  $item->id . '</td>' : "";
        $longStr = /*$runDesc . $lineId .*/ $str . $runUpdated;
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
            $td = '<td class="border" colspan=5 style="width:190px">'  . $this->formatAttachmentRender($item->insp_photos) . '</td>';
            return "<tr  class=' bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" . $td . "</tr>";
        }
        return '';
    }

    private function createStrHtmlComment($item)
    {
        if (!is_null($item->value_comment)) {
            $td = "<td class='border p-3' colspan = 5 style='width:190px'>{$item->value_comment}</td>";
            return "<tr class=' bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" . $td . "</tr>";
        }
        return "<tr> </tr>";
    }
    protected function formatAttachmentRender($item)
    {
        $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
        $strCenter = "";
        foreach ($item as  $attachment) {
            $urlThumbnail = $path . $attachment->url_thumbnail;
            $urlMedia = $path . $attachment->url_media;
            $fileName = $attachment->filename;
            $strCenter .= "
                            <div class='border-gray-300 relative h-full flex mx-1 flex-col items-center p-1 border rounded-lg  group/item overflow-hidden bg-inherit'>
                                <img class='' src='$urlThumbnail' alt='$fileName' />
                                <div class='invisible flex justify-center hover:bg-[#00000080] group-hover/item:visible before:absolute before:-inset-1  before:bg-[#00000080]'>
                                        <a title='$fileName' href='$urlMedia' target='_blank' class='hover:underline text-white hover:text-blue-500 px-2 absolute top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] text-lg text-center w-full'>
                                            <span class='text-sm'><i class='fa-sharp fa-solid fa-eye text-2xl '></i></span>
                                        </a>
                                </div>
                            </div>";
        };
        $strHead = "<div class='flex flex-col container mx-auto w-full' >
                    <div class='grid grid-cols-5 lg:gap-3 md:gap-2 sm:gap-1 '>";
        $strTail = "</div></div>";
        return $strHead . $strCenter . $strTail;
    }
    protected function createDataSourceTableRun($value)
    {
        $html = "<table class = 'w-full text-sm text-left text-gray-500 dark:text-gray-400'>" . "<tbody>" . $this->transFormLine($value) . "</tbody>" . "</table>";
        return $html;
    }
}
