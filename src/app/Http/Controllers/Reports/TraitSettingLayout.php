<?php

namespace App\Http\Controllers\Reports;


trait TraitSettingLayout
{
    public function layout($valueOptionPrint)
    {
        $layout = '';
        switch ($valueOptionPrint) {
            case 'landscape':
            $layout = 'w-[1400px] min-h-[1000px]';
            break;
            case 'portrait':
            default:
                $layout = 'w-[1000px] min-h-[1360px]';
                break;
        }
        return $layout;    
    }
}
