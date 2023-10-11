<div class="page-break-after">
    <div class="flex items-center justify-center no-print">
        <x-controls.text2 type="search" class="w-[550px] mr-1 my-2" name="mySearch_{{$id}}"
        placeholder="Press ENTER to search, and Press SPACE to pan to the next result"
        value="" onkeypress="if (event.keyCode === 13) searchDiagram({{$id}})" />
        <x-renderer.button type="secondary" onClick="searchDiagram({{$id}})" class="w-20" >Search</x-renderer.button>
    </div>
    <div class="relative">
        <div id="myOverviewDiv_{{$id}}" class="no-print w-60 h-60 absolute top-1 left-1 border bg-gray-100 border-gray-100"
        style="cursor: move; z-index:19;">
            <canvas tabindex="0" width="198" height="98" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none; width: 198px; height: 98px; cursor: move;"></canvas>
            <div style="position: absolute; overflow: auto; width: 198px; height: 98px; z-index: 1;">
                <div style="position: absolute; width: 1px; height: 1px;"></div>
            </div>
        </div> <!-- Styled in a <style> tag at the top of the html page -->
        <div id="" class="only-print w-60 h-60 absolute top-1 left-1 border bg-gray-100 border-gray-100" style="cursor: move; z-index:19;"></div>
        <div id="myDiagramDiv_{{$id}}" class="w-full h-screen bg-white border border-1 border-gray-600"
        {{-- style="border: 1px solid black; position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0);" --}}
        >
            <canvas tabindex="0"  111 class="w-full h-screen" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none;">This text is displayed if your browser does not support the Canvas HTML element.</canvas>
            {{-- <div style="position: absolute; overflow: auto; width: 398px; height: 398px; z-index: 1;">
            <div style="position: absolute; width: 1px; height: 1px;">JS ERROR</div>
            </div> --}}
        </div>
    </div>
    <script>window.addEventListener('DOMContentLoaded', init({{$id}}, @json($dataSource)));</script>
</div>