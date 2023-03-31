@if($paginator->total() && $paginator->hasPages())
{{-- Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} --}}
<div class="flex items-center">
    Total <b class="px-1">{{ Str::humanReadable($paginator->total()) }}</b>
</div>
@endif