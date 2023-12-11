@if(!$hiddenLabel)
    <label class='text-gray-700 dark:text-gray-300  px-3 block text-base' title='{{$title}}'>
    @if($control == 'relationship_renderer')
        @if($action !== 'create')
            @php
            $subModel = ($item::$eloquentParams[$prop['columnName']][1]);
            $subTable = (new($subModel))->getTable();
            $href = "/dashboard/$subTable";
            @endphp
            <a href="{{$href}}">{{$label}}</a>
        @else
            {{-- Hide the label --}}
        @endif
    @else
       {!! $label !!}  {{-- &amp handled by !! --}}
    @endif
    
    {!!$isRequired ? "<span class='text-red-400'>*</span>" : "" !!}
    {{-- @if(!$hiddenLabel)  --}}
    <br /> 
    {{-- @endif --}}
    <span class="flex justify-end">{!!$iconJson!!}</span>
    {{-- @if(!$hiddenLabel)  --}}
    <i>{!! $labelExtra !!}</i> </label>
    {{-- @endif --}}
@endif