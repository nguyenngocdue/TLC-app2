@extends('layouts.app-guest')

@section('content')
<x-landing-page.header :dataSource="$header"/>
<x-landing-page.video :dataSource="$video" />
<x-landing-page.carousel :dataSource="$carousel"/>
<x-landing-page.what-you-get/>
<x-landing-page.process />
<x-landing-page.team :dataSource="$team"/>
<x-landing-page.faq :dataSource="$faq"/>
<x-landing-page.testimonial :dataSource="$testimonial"/>
<x-landing-page.footer/>
<x-renderer.button-scroll />
@endsection



