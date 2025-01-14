@extends('layouts.applearn')
@section('topTitle', 'Component Demo')

@section('content')

@php
$activeClass = "bg-white border-t border-r border-l -mb-px";
@endphp

<x-renderer.heading level=3 class='text-center'><a href="/components">Components</a></x-renderer.heading>

<!-- Tabs -->
<div class="bg-gray-100 h-screen">
<x-renderer.tab-pane class="mx-2" id="e07ff0dbf9a2afd616aca8e7a85921e2" :tabs="$tabPaneDataSource">
    <!-- Tab Contents -->
    <div id="tab-contents-e07ff0dbf9a2afd616aca8e7a85921e2">
        <div id="static" class="bg-white p-4 hidden">
            <x-demo.demo-static :tagColumns="$tagColumns" :tagDataSource="$tagDataSource" :gridDataSource="$gridDataSource" />
        </div>
        <div id="data_display" class="bg-white p-4 hidden">
            <x-demo.demo-data-display 
                    :tableColumns="$tableColumns" 
                    :tableDataSource="$tableDataSource" 
                    :tableDataHeader="$tableDataHeader"
                    :tableDataSourceForRegister="$tableDataSourceForRegister"
                    :tableColumnsForRegister="$tableColumnsForRegister"

                    :tableSpanColumns="$tableSpanColumns"
                    :tableSpanDataSource="$tableSpanDataSource"
                    />
        </div>
        <div id="data_entry" class="bg-white p-4 hidden">
            <x-demo.demo-data-entry :dropdownCell="$dropdownCell" />
        </div>
        {{-- <div id="icons" class="bg-white p-4 hidden1">
            <x-demo.demo-icons :columns="$iconsColumns" :dataSource="$iconsDataSource"/>
        </div> --}}
        <div id="attachments" class="bg-white p-4 hidden">
            <x-demo.demo-attachment-comment :attachmentData="$attachmentData" :attachmentData2="$attachmentData2" :dataComment="$dataComment" />
        </div>
        <div id="editable_tables" class="bg-white p-4 hidden">
            <x-demo.demo-data-table :tableEditableColumns="$tableEditableColumns" :tableDataSource="$tableDataSource" />
        </div>
        <div id="navigation" class="bg-white p-4 hidden">
            <x-demo.demo-navigation :tabData1="$tabData1" :tabData2="$tabData2" />
        </div>
        <div id="feedbacks" class="bg-white p-4 hidden ">
            <x-demo.demo-feedback :dataSourceProgressBar="$dataSourceProgressBar"/>
        </div>
        <div id="listeners" class="bg-white p-4 hidden">
            <x-demo.demo-listener />
        </div>
        {{-- <div id="modecontrols" class="bg-white p-4 hidden">
        <x-demo.demo-modes-control :dataSource="$dataSource" :itemsSelected="$itemsSelected" />
        </div> --}}
        <div id="pivot_tables" class="bg-white p-4 hidden">
            <x-demo.demo-pivot-table :columns="$pivotTableColumns" :dataSource="$pivotTableDataSource" />
        </div>
        <div id="pivot_tables2" class="bg-white p-4 hidden">
            <x-demo.demo-pivot-table2 :columns="$pivotTableColumns2" :dataSource="$pivotTableDataSource2" />
        </div>
        <div id="charts" class="bg-white p-4">
            <x-demo.demo-line-chart/>
            <x-demo.demo-column-chart/>
            <x-demo.demo-stacked-column-chart/>
            <x-demo.demo-pie-chart/>
            <x-demo.demo-mixed-chart/>
        </div>
    </div>
</x-renderer.tab-pane>
</div>

@once
<script type="text/javascript">
const initTab = (tabId) => {
    let tabsContainer = document.querySelector("#tabs-" + tabId);
    let tabTogglers = tabsContainer.querySelectorAll("#tabs-" + tabId + " a");
    tabTogglers.forEach(function(toggler) {
        toggler.addEventListener("click", function(e) {
            e.preventDefault();
            let tabName = this.getAttribute("href");
            let tabContents = document.querySelector("#tab-contents-" + tabId);
            for (let i = 0; i < tabContents.children.length; i++) {
                tabTogglers[i].parentElement.classList.remove("border-t", "border-r", "border-l", "-mb-px", "bg-white");
                tabTogglers[i].parentElement.classList.add("bg-gray-200");
                tabContents.children[i].classList.remove("hidden");
                if ("#" + tabContents.children[i].id === tabName) continue;
                //Hide all children of other tabs
                tabContents.children[i].classList.add("hidden");
            }
            e.target.parentElement.classList.add("border-t", "border-r", "border-l", "-mb-px", "bg-white");
        });
    });
}
</script>
@endonce

<script>
    initTab('e07ff0dbf9a2afd616aca8e7a85921e2');
</script>
@endsection
