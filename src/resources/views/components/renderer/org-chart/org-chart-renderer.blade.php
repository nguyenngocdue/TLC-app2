<x-renderer.heading level=3 xalign="center">{{$department? strtoupper($department->name) : ""}}</x-renderer.heading>
<div class="page-break-after">
    <div class="relative">
        <div id="myOverviewDiv_{{$id}}" class="no-print w-60 h-60 absolute top-1 left-1 border bg-gray-100 border-gray-100"
        style="cursor: move; z-index:19;">
            <canvas tabindex="0" width="198" height="98" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none; width: 198px; height: 98px; cursor: move;"></canvas>
            <div style="position: absolute; overflow: auto; width: 198px; height: 98px; z-index: 1;">
                <div style="position: absolute; width: 1px; height: 1px;"></div>
            </div>
        </div> <!-- Styled in a <style> tag at the top of the html page -->
        
        <div id="" class="only-print w-60 h-60 absolute top-1 left-1 border bg-gray-100 border-gray-100" style="cursor: move; z-index:19;"></div>
        
        <div id="myDiagramDiv_{{$id}}" class="w-full h-screen bg-white">
            <canvas tabindex="0" 111 class="w-full h-screen" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none;">
                This text is displayed if your browser does not support the Canvas HTML element.
            </canvas>
        </div>
    </div>
    <script>window.addEventListener('DOMContentLoaded', init({{$id}}, {{$zoomToFit?1:0}}, @json($dataSource)));</script>
</div>