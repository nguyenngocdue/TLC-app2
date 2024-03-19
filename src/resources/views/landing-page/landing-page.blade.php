@extends('layouts.app-guest')

@section('content')
<x-landing-page.header/>
<x-landing-page.video :dataSource="$video" />
<x-landing-page.carousel :dataSource="$carousel"/>
<x-landing-page.what-you-get/>
<x-landing-page.process />
<x-landing-page.team />
<x-landing-page.testimonial :dataSource="$testimonial"/>
<x-landing-page.footer/>


@endsection



