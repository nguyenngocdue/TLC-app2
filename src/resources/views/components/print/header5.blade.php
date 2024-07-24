<x-print.letter-head5 showId={{$qrId}} type={{$type}} :dataSource="$dataSource" />
{{-- <x-renderer.heading level=3 xalign='center'>{{$title}}</x-renderer.heading> --}}
<div class="text-2xl-vw uppercase font-bold text-center p-1vw">{{$title}}</div>
{!!$contentHeader!!}