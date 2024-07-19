@extends('layouts.app-guest')

@section('content')
<x-home-web-page.header :dataSource="$header"/>

<x-home-web-page.access-app/>
<x-home-web-page.terms-conditions/>
<x-home-web-page.privacy-policy />
<x-home-web-page.contact-support/>
<x-home-web-page.careers />

<x-home-web-page.footer/>
<x-renderer.button-scroll />
@endsection



