<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class Signature2 extends Component
{
    static $count = 0;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $ownerIdColumnName = null,
        private $value = null,
        private $signatureComment = null,
        private $signatureCommentColumnName = null,
        private $signedPersonId = null,
        private $debug = false,
        private $updatable = true,
        private $categoryColumnName = null,
        private $category = null,
        private $signableTypeColumnName = null,
        private $signableType = null,
    ) {
        //
        static::$count++;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // $this->debug = true;
        $value_decoded = (htmlspecialchars_decode($this->value));
        return view(
            'components.controls.signature2',
            [
                'name' => $this->name,
                'value' => $this->value,
                'value_decoded' => $value_decoded,
                'signatureComment' => $this->signatureComment,
                'signatureCommentColumnName' => $this->signatureCommentColumnName,
                'count' => static::$count,
                'input_or_hidden' => $this->debug ? "text" : "hidden",
                'updatable' => $this->updatable,
                'debug' => $this->debug,
                'ownerIdColumnName' => $this->ownerIdColumnName,
                'signedPersonId' => $this->signedPersonId,
                'cuid' => CurrentUser::id(),
                'categoryColumnName' => $this->categoryColumnName,
                'category' => $this->category,
                'signableTypeColumnName' => $this->signableTypeColumnName,
                'signableType' => $this->signableType,
            ]
        );
    }
}
