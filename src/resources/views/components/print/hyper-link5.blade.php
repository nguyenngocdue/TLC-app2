<div title="{{$title}}" class="w-full py-1">
    <a href="{{$href}}" target="_blank"  >
        <x-renderer.button block={{true}} outline={{!$isNotFound}} type="{{$isNotFound ? 'secondary' : 'danger'}}" size='sm' onClick='$onClick'>
            {{$label}}
        </x-renderer.button>
    </a>
</div>
