@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

{{-- <x-renderer.table3
    :columns="$table03a['columns']"
    :dataSource="$table03['dataSource']"
    
    showNo=1
    tableDebug="1"
    tableTrueWidth="1"

    header="Edit Mode"
    >
</x-renderer.table>

<br/>
<hr/>
<br/> --}}

<x-renderer.table3
    :columns="$table03['columns']"
    :dataSource="$table03['dataSource']"
    
    showNo=1
    tableDebug="1"
    tableTrueWidth="1"

    header="View Mode"
    >
</x-renderer.table>

<br/>
<hr/>
<br/>

{{-- 
<x-renderer.table3
    :columns="$table03['columns']"
    :dataSource="$table03['dataSource']"
    
    showNo=1
    tableDebug="1"
    tableTrueWidth="1"

    header="Read-Only Mode"
    >
</x-renderer.table>

<br/>
<hr/>
<br/> --}}


{{-- <x-renderer.table3
    :columns="$table01['columns']"
    :dataSource="$table01['dataSource']"
    
    showNo=1
    tableDebug="1"
    tableTrueWidth="1"

    header="Table Rotate 45 Width = 100"
    rotate45Width=100
    >
</x-renderer.table>

<br/>
<hr/>
<br/>

<x-renderer.table3
    :columns="$table01['columns']"
    :dataSource="$table01['dataSource']"
    
    showNo=1
    tableDebug="1"
    tableTrueWidth="1"

    header="Table Rotate 45 Width = 200"
    rotate45Width=200
    >
</x-renderer.table>

<br/>
<hr/>
<br/>

<x-renderer.table3
    :columns="$table01['columns']"
    :dataSource="$table01['dataSource']"
    
    showNo=1
    tableDebug="1"
    tableTrueWidth="1"

    header="Table Rotate 45 Width = 300"
    rotate45Width=300
    >
</x-renderer.table>

<br/>
<hr/>
<br/>


<x-renderer.table3
    :columns="$table01['columns']"
    :dataSource="$table01['dataSource']"
    
    showNo=1
    tableDebug="1"
    tableTrueWidth="1"

    header="Table Rotate 45 Width = 400"
    rotate45Width=400
    >
</x-renderer.table>

<br/>
<hr/>
<br/> --}}

<x-renderer.table3
    :columns="$table02['columns']"
    :dataSource="$table02['dataSource']"
    :dataHeader="$table02['sndHeader']"
    
    maxH="300"
    
    showNo=1
    tableDebug="1"
    tableTrueWidth="1"

    showPaginationTop={{true}}
    topLeftControl="Top left controls"
    topCenterControl="Top center controls"
    topRightControl="Top right controls"

    showPaginationBottom={{true}}
    bottomLeftControl="Bottom left controls"
    bottomCenterControl="Bottom center controls"
    bottomRightControl="Bottom right controls"

    header="Table Header"
    footer="Table Footer"

    >
</x-renderer.table>

<br/>
<hr/>
<br/>

<x-renderer.table3
    :columns="$table01['columns']"
    :dataSource="$table01['dataSource']"
    
    showNo=1
    tableDebug="1"
    tableTrueWidth="1"

    showPaginationTop={{true}}
    topLeftControl="Top left controls"
    topCenterControl="Top center controls"
    topRightControl="Top right controls"

    showPaginationBottom={{true}}
    bottomLeftControl="Bottom left controls"
    bottomCenterControl="Bottom center controls"
    bottomRightControl="Bottom right controls"

    header="Table Header"
    footer="Table Footer"

    >
</x-renderer.table>

<x-renderer.table3></x-renderer.table3>

<x-renderer.table3 :columns="$table01['columns']"></x-renderer.table3>


@endsection 