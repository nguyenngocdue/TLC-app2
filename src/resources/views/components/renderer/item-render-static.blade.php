@switch($control)
@case('z_page_break')
<x-renderer.page-break />
@case('z_h1')
<x-renderer.heading id="{{Str::slug($label)}}" title="{{$title}}" level=1 class="text-{{$xalign}}" labelExtra="{{$labelExtra}}">{{$label}}</x-renderer.heading>
@break
@case('z_h2')
<x-renderer.heading id="{{Str::slug($label)}}" title="{{$title}}" level=2 class="text-{{$xalign}}" labelExtra="{{$labelExtra}}">{{$label}}</x-renderer.heading>
@break
@case('z_h3')
<x-renderer.heading id="{{Str::slug($label)}}" title="{{$title}}" level=3 class="text-{{$xalign}}" labelExtra="{{$labelExtra}}">{{$label}}</x-renderer.heading>
@break
@case('z_h4')
<x-renderer.heading id="{{Str::slug($label)}}" title="{{$title}}" level=4 class="text-{{$xalign}}" labelExtra="{{$labelExtra}}">{{$label}}</x-renderer.heading>
@break
@case('z_h5')
<x-renderer.heading id="{{Str::slug($label)}}" title="{{$title}}" level=5 class="text-{{$xalign}}" labelExtra="{{$labelExtra}}">{{$label}}</x-renderer.heading>
@break
@case('z_h6_base')
<x-renderer.heading id="{{Str::slug($label)}}" title="{{$title}}" class="text-{{$xalign}}" labelExtra="{{$labelExtra}}">{{$label}}</x-renderer.heading>
@break
@case('z_divider')
<x-renderer.divider />
@break

@default
<x-feedback.alert type="warning" title="{{$title}}" message="The control [{{$control}}] is not available" />
@break
@endswitch