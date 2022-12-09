@error($colName)

{{-- @section('alertValidation') --}}
@php
$customMes = App\Helpers\Helper::customMessageValidation($message, $colName, $labelName);
@endphp
<span class="text-xs text-red-400 font-light" role="alert">
    <strong id="{{$colName}}">{!!$customMes!!}</strong>
</span>
{{-- @endsection --}}
@enderror
