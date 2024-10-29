<div class="flex justify-center bg-body bg-only-print print-responsive">
    <div class="p-4vw w-90vw mx-auto bg-white">
        <div id="{{Str::slug($sheet->name)."_".$sheet->id}}"></div>
        <x-print.letter-head5 showId="{{$sheet->id}}" type="{{$type}}"  />
        <x-renderer.heading level=3 class='text-center uppercase font-bold p-1vw'>{{Str::singular($topTitle)}}</x-renderer.heading>
        
        <x-print.insp-chklst.check-point2-project-box-print :projectBox="$projectBox" />
        <x-renderer.heading level=4 class='text-center uppercase font-bold p-1vw'>{{$sheet->name}}</x-renderer.heading>
        
        <x-renderer.progress-bar :dataSource="$progressData" height="40px" />
        <x-print.insp-chklst.check-point2-group-print :groupedCheckpoints="$groupedCheckpoints"/>
        <x-print.insp-chklst.check-point2-signoff-print :signOff="$signOff"/>
    </div>
</div>