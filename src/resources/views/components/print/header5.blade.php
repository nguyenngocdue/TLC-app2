<x-print.letter-head5 showId={{$qrId}} type={{$type}} :dataSource="$dataSource" />
<x-renderer.heading level=3 class='text-center uppercase p-1vw font-bold'>{{$title}}</x-renderer.heading>
{!!$contentHeader!!}