<x-renderer.table tableName="{{$table01ROName}}" :columns="$readOnlyColumns" :dataSource="$dataSource" showNo="{{$showNo?1:0}}" footer="{{$tableFooter}}" noCss="{{$noCss}}" />

@if(!$readOnly && $createANewForm)
    <x-renderer.button type='success' href='{!!$href!!}' >Create a new {{strtoupper( Str::singular($tableName))}}</x-renderer.button>
@endif