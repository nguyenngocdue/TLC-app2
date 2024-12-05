@foreach ($dataPerPage as $pageKey => $values)
    @php
        $currentParams = $values['updatedParams'];
        $page = $values['page'];
    @endphp
        <x-reports2.report-page :page="$page" :report="$report" :currentParams="$currentParams" hasIteratorBlock="{{$hasIteratorBlock}}"/>
@endforeach