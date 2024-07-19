@extends('layouts.app-guest')

@section('content')
<x-home-web-page.header :dataSource="$header"/>

<x-home-web-page.our-products/>
<x-home-web-page.our-services/>
<x-home-web-page.know-our-team/>
<x-home-web-page.faq/>
<x-home-web-page.testimonial/>

<x-home-web-page.footer/>
<x-renderer.button-scroll />
@endsection



