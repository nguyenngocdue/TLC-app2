@php
    //dd($pages)
@endphp
@foreach ($pages as $page)
    <x-reports2.page-report
        :width="$page['width'] ?? 0"
        :height="$page['height'] ?? 0"
        :isFullWidth="$page['is_full_width'] ?? false"
        :isLandscape="$page['is_landscape'] ?? false" 
        :content="$page"
        :letterHeadId="$page['letter_head_id'] ?? 1"
        :letterFooterId="$page['pgaletter_footer_id'] ?? 1"
        :blocks="$page['blocks'] ?? []"
        :background="$page['url_media'] ?? ''"
        />
@endforeach

