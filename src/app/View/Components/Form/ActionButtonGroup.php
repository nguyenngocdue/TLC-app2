<?php

namespace App\View\Components\Form;

use App\Http\Controllers\Workflow\LibApps;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class ActionButtonGroup extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type = null,
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
        $qrApps = LibApps::getByShowRenderer('qr-app-renderer');
        // dump($qrApps, $this->type);
        $showQrList6Button = in_array(Str::singular($this->type), $qrApps);
        return view('components.form.action-button-group', [
            'type' => $this->type,
            'showQrList6Button' => $showQrList6Button,
        ]);
    }
}
