@extends('layouts.app')
@section('content')

@php
function a ($item1, $item2) {
            // dump($item1);
            // dump($item2);
            foreach ($sortOrders as $field => $sortOrder) {
                $comparison = $item1[$field] <=> $item2[$field];
                $cmpStr = $comparison > 0?">":($comparison<0?"<":"=");
                Log::info("$field ".$item1[$field]." ".$cmpStr." ".$item2[$field]);
                // dump($dataOutput);
                if ($comparison) {
                    // Log::info('Khac 0 vvv');
                    // Log::info('Item1:'.$item1[$field]);
                    // Log::info($comparison);
                    // Log::info('Item2:'.$item2[$field]);
                    // Log::info('Khac 0 ^^^');
                    return ($sortOrder === 'asc') ? $comparison : -$comparison;
                }
            }
            return 0;
        }
        
@endphp
    <x-renderer.report.pivot-table key="{{$key}}" :dataSource="$dataSource"/>
@endsection

