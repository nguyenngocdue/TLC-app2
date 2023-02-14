@error($name)
@php $messages = $errors->default->getMessages()[$name]; @endphp
{{-- <strong class="text-red-400">{{$label}}:</strong> --}}
<span class="text-xs text-red-400 font-light" role="alert">
    <ul class="mt-1.5 ml-4 text-red-700 list-disc list-inside">
        @foreach($messages as $message)
        <li>{!!$message!!}</li>
        @endforeach
    </ul>
</span>
@enderror
