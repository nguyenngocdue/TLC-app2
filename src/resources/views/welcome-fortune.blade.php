@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')
<form>
    <x-renderer.table3
    :columns="$table05a['columns']"
    :dataSource="$table05a['dataSource']"
    
    showNo=1
    tableDebug="1"
    tableTrueWidth="1"

    header="Edit Mode - Thumbnail and AvatarUser (table05)" 
    rowHeight=87

    orderable="1"
    duplicatable="1"
    deletable="1"

    >
</x-renderer.table>

<br/>
<hr/>
<br/>

{{-- <x-renderer.table3
    :columns="$table05['columns']"
    :dataSource="$table05['dataSource']"
    
    showNo=1
    tableDebug="1"
    tableTrueWidth="1"

    header="View All Mode - Thumbnail and AvatarUser (table05)" 
    rowHeight=101
    >
</x-renderer.table>

<br/>
<hr/>
<br/>

<x-renderer.table3
    :columns="$table04['columns']"
    :dataSource="$table04['dataSource']"
    
    showNo=1
    tableDebug="1"
    tableTrueWidth="1"

    header="View All Mode (table04)"
    >
</x-renderer.table>

<br/>
<hr/>
<br/>

<x-renderer.table3
    :columns="$table03a['columns']"
    :dataSource="$table03['dataSource']"
    
    showNo=1
    tableDebug="1"
    tableTrueWidth="1"

    header="Edit Mode (table03a)"

    orderable="1"
    duplicatable="1"
    deletable="1"
    >
</x-renderer.table>

<br/>
<hr/>
<br/>

<x-renderer.table3
    :columns="$table03['columns']"
    :dataSource="$table03['dataSource']"
    
    showNo=1
    tableDebug="1"
    tableTrueWidth="1"

    header="View Mode  (table03)"
    animationDelay=1000
    >
</x-renderer.table>

<br/>
<hr/>
<br/>


<x-renderer.table3
    :columns="$table03['columns']"
    :dataSource="$table03['dataSource']"
    
    showNo=1
    tableDebug="1"
    tableTrueWidth="1"

    header="Read-Only Mode (table03)"
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

    header="Table Rotate 45 Width = 100 (table01)"
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

    header="Table Rotate 45 Width = 200 (table01)"
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

    header="Table Rotate 45 Width = 300 (table01)"
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

    header="Table Rotate 45 Width = 400 (table01)"
    rotate45Width=400
    >
</x-renderer.table>

<br/>
<hr/>
<br/> --}}

{{-- <x-renderer.table3
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

    header="table02: 10 lines with 2 headers and orderable"
    footer="Table Footer"

    orderable="1"
    duplicatable="1"
    deletable="1"
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

    header="Table Header (table01)"
    footer="Table Footer"

    >
</x-renderer.table>

<x-renderer.table3></x-renderer.table3>

<x-renderer.table3 :columns="$table01['columns']"></x-renderer.table3> --}}


<button class="rounded border p-1 m-1">SUBMIT</button>
</form>
@endsection 