<div class="grid grid-cols-12 gap-1">
    <div class="col-span-2">
        <div class="no-print justify-end pl-4 pt-5">
            <x-reports.dropdown8 title="Mode" name="forward_to_mode" routeName="report-prod_sequence_020"
                :allowClear="false" :dataSource="$keysNames" typeReport="reports" entity="prod_sequences" modeOption="020"
                :itemsSelected="$params" />
        </div>
    </div>

    <div class="col-span-10">
        <div class="px-4">
        </div>
    </div>
</div>

@php
    $type = 'prod_sequences';
    $viewportParams = [
        'project_id' => '75',
        'sub_project_id' => '116',
        'prod_routing_id' => '80',
        'prod_routing_link_id' => [],
        'prod_discipline_id' => '10',
    ];

@endphp




<div class="grid grid-row-1 w-full">
    <div class="grid grid-cols-12 gap-4 items-baseline">
        {{--      <div id="" class="col-span-2">
            <span class='px-1'>Project</span>
            <x-renderer.report2.filter-report-item />
        </div>

 --}}

        @foreach ($filterDetails as $filter)
            @php
                $title = is_NUll($x = $filter->getColumn->title) ? 'Set title for column' : $x;
            @endphp
            <div id="" class="col-span-2">
                <a target="blank" href="{{ route('rp_report_filter_details.edit', $filter->id) }}">
                    <span class='px-1'>{{$title}}</span>
                </a>
                {{-- <x-renderer.report2.filter-report-item-listener :filterDetail="$filter" /> --}}
                <x-renderer.report2.filter-report-item :filterDetail="$filter" />
            </div>
        @endforeach
    </div>
</div>


<form action="{{ route('updateUserSettings') }}" method="post">
    @csrf
    @method('PUT')
    <input type="hidden" name="action" value="updateViewAllMatrix" />
    <input type="hidden" name="_entity" value="{{ $type }}" />
    <div class="bg-white rounded w-full my-2 p-2">
        <div class="w-full my-1 grid grid-cols-12 gap-2">


        </div>
        <x-renderer.button type='primary' htmlType="submit" icon="fa-sharp fa-solid fa-check">Apply
            Filter</x-renderer.button>
    </div>
</form>
