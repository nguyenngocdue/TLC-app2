<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class ShowItem extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $label, private $colSpan, private $contents, private $colName)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        return function (array $data) {
            $contents = $this->contents;
            $colName = $this->colName;
            if (is_array($contents)) {
                $attachmentData = [$colName => $contents];
                // dd($attachmentData);
                // return "<x-renderer.attachment :attachmentData='123'/>";
                return "attachment";
            }
            $label = $this->label;
            $colSpan = $this->colSpan;
            $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';

            // dump($contents);
            return "
            <div class='col-span-{$colSpan} grid'>
                <div class='grid grid-row-1'>
                    <div class='grid grid-cols-12  '>
                        <label class='p-2 border bg-gray-50 h-full w-full flex  col-span-{{24/$colSpan}} items-center   justify-end col-start-1 text-end'>{$label}</label>
                        <span class='p-2  border col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} '>{$contents}</span>
                    </div>
                </div>
            </div>
            ";
        };
    }
}
