@props(['attachment', 'model', 'relationship'])

@if (count($items) == 0)
<span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold leading-tight text-green-700 dark:bg-green-700 dark:text-green-100">
    {{ count($items) }} items
</span>
@elseif (count($items) <= 3) <div class="flex items-center text-sm">
    @foreach ($items as $item)
    @php
    $imageExtension = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
    @endphp
    @if (in_array($item->extension, $imageExtension))
    @php
    $url_thumbnail = $path . $item->url_thumbnail;
    $url_media = $path . $item->url_media;
    @endphp
    <div class="relative mr-3 hidden h-12 w-12 md:block" title="{{ $item->filename }}">
        <a href="{{ $url_media }}">
            <img class="h-full w-full object-cover" src="{{ $url_thumbnail }}" alt="{{ $item->filename }}" />
        </a>
    </div>
    @else
    @php
    @endphp
    <div class="relative mr-3 hidden h-12 w-12 md:block" title="{{ $item->filename }}">
        <a href="{{ route('upload_add.download', $item->id) }}">
            <img class="h-full w-full object-cover" src="{{ asset('logo/file.png') }}" alt="{{ $item->filename }}" />
        </a>
    </div>
    @endif
    @endforeach
    </div>
    @elseif (count($items) > 3)
    <div class="flex items-center text-sm">
        @foreach ($itemShows as $item)
        @php
        $imageExtension = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
        @endphp
        @if (in_array($item->extension, $imageExtension))
        @php
        $url_thumbnail = $path . $item->url_thumbnail;
        $url_media = $path . $item->url_media;
        @endphp
        <div class="relative mr-3 hidden h-12 w-12 md:block" title="{{ $item->filename }}">
            <a href="{{ $url_media }}">
                <img class="h-full w-full object-cover" src="{{ $url_thumbnail }}" alt="{{ $item->filename }}" />
            </a>
        </div>
        @else
        <div class="relative mr-3 hidden h-12 w-12 md:block" title="{{ $item->filename }}">
            <a href="{{ route('upload_add.download', $item->id) }}">
                <img class="h-full w-full object-cover" src="{{ asset('logo/file.png') }}" alt="{{ $item->filename }}" />
            </a>
        </div>
        @endif
        @endforeach
    </div>
    @if (isset($countRemaining))
    <span class="mt-2 rounded-full bg-green-100 px-2 py-1 text-xs font-semibold leading-tight text-green-700 dark:bg-green-700 dark:text-green-100">
        {{ $countRemaining }} more
    </span>
    @endif
    @endif
