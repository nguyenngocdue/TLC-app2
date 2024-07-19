@extends('layouts.app-guest')

@section('content')
<x-home-web-page.header :dataSource="$header"/>

<x-home-web-page.carousel/>
<x-home-web-page.what-you-get/>
<x-home-web-page.process />
<x-home-web-page.team/>
{{-- <x-home-web-page.testimonial :dataSource="$testimonial"/> --}}
{{-- <x-home-web-page.faq :dataSource="$faq"/> --}}
<x-home-web-page.video/>

<x-home-web-page.footer/>
<x-renderer.button-scroll />
@endsection



