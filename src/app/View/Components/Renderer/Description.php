<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Description extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $label,
        private $colSpan,
        private $contents,
        private $colName,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $contents = $this->contents;
        $colName = $this->colName;
        dump($colName);
        dump($contents);

        if (is_array($contents)) {
            $attachmentData = [$colName => $contents];
            dd($attachmentData);
            // dd($attachmentData);
            // return "<x-renderer.attachment :attachmentData='123'/>";
            return "attachment";
        }
        $label = $this->label;
        $colSpan = $this->colSpan;
        $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';

        return view('components.renderer.description', [
            'label' => $label,
            'colSpan' => $colSpan,
            'contents' => $contents,
        ]);
    }
}
