<?php

namespace App\View\Components\Reports2;

class ColumnReport
{
    private static $instance = null;
    protected $block;
    private function __construct($block)
    {
        $this->block = $block;
    }

    public static function getInstance($block)
    {
        if (self::$instance == null) self::$instance = new ColumnReport($block);
        return self::$instance;
    }

    function defaultColumnsOnEmptyQuery()
    {
        return $this->block->getLines->where('is_active', true)
            ->select('title', 'data_index')
            ->map(function ($item) {
                return [
                    'title' => $item['title'] ?? $item['data_index'],
                    'dataIndex' => $item['data_index']
                ];
            })
            ->toArray();
    }
}
