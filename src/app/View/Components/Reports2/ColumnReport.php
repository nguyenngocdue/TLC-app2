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
        if (self::$instance == null) {
            self::$instance = new ColumnReport($block);
        }
        return self::$instance;
    }

    function defaultColumnsOnEmptyQuery(){
        $columns = $this->block->getLines->where('is_active', true)->select('title', 'data_index');
        $columns = $columns->map(function($item) {
            $colunm = [];
            if(isset($item['title']) && is_null($item['title'])){
                $colunm['title'] = $item['data_index'];
            }else {
                $colunm['title'] = $item['title'];
            }
            $colunm['dataIndex'] = $item['data_index'];
            return $colunm;
        });
        return $columns->toArray();
    }
    
}
