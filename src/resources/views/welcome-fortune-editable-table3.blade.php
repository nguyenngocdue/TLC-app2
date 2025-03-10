@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')
<form id="virtual-table-form" action="/welcome-fortune" method="POST">
    @csrf
    @method('POST')
{{-- <x-renderer.table3
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
<br/> --}}

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
<br/> --}}

{{-- <x-renderer.table3
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
 --}}

@php
    $showButton = [
        "AddAnItem" => 1,
        "AddFromList" => 1,
        "CloneFromTemplate" => 1,
        "Recalculate" => 1,
    ];
    $envConfig = [
        "entityParentType" => "coc",
        "ccc" => "ddd",
    ];
@endphp

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

    :showButton="$showButton"
    :envConfig="$envConfig"

    showPaginationTop={{true}}
    
    showPaginationBottom={{true}}
    bottomLeftControl="masterDuplicateDelete"
    bottomRightControl="helloWorld"

    >
</x-renderer.table>

<br/>
<hr/>
<br/>



{{-- <x-renderer.table3
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
<br/> --}}

{{--
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
<div id="hidden-input-container">
HHHHH
</div>

</form>


<script>

function populateHiddenInputs() {
    const form = document.getElementById('virtual-table-form');
    const hiddenInputContainer = document.getElementById('hidden-input-container');

    const tableName = 'table11';
    console.log(tableData[tableName], tableColumns[tableName])

    hiddenInputContainer.innerHTML = '';
    const allRows = tableData[tableName].data
    console.log(allRows)
    const columns = tableColumns[tableName]
    console.log(columns)

    allRows.forEach((row, rowIndex) => {
        columns.forEach(column=>{
            const {dataIndex} = column;
            if(dataIndex === '_no_') return;
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `${tableName}[ln_${rowIndex}][${column.dataIndex}]`;

            // console.log(row[dataIndex])
            input.value = row[dataIndex];
            hiddenInputContainer.appendChild(input);
        })
        console.log("created row", rowIndex)
    });
    console.log(hiddenInputContainer)
}

const form = document.getElementById("virtual-table-form") 

form.addEventListener("submit", (event) => {
    event.preventDefault();
    populateHiddenInputs();
    form.submit();
})
</script>


@endsection 

