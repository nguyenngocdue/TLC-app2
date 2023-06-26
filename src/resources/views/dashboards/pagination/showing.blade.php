@php $total = $paginator->total(); @endphp
{{-- @if($total && $paginator->hasPages()) --}}
{{-- Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} --}}
<div class="flex items-center" component="pagination/showing">
    Total <b class="px-1" title="{{$total}}">{{ Str::humanReadable($total)}}</b>{{Str::plural("item",$total)}}
</div>
{{-- @endif --}}