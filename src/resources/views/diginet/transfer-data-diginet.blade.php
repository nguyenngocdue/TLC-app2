@extends('layouts.app')
@section('topTitle', 'Retrieve Diginet Data')
@section('title', '')

@section('content')

<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<div class='p-10'>
    <table class="table-auto w-full text-sm text-left text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 text-center">
            <tr>
                <th class="px-4 py-3 border border-gray-200 dark:border-gray-700 text-lg">Year</th>
                @for ($month = 1; $month <= 12; $month++)
                    <th class="px-4 py-3 border border-gray-200 dark:border-gray-700 text-lg">T{{ $month }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @for ($year = 2023; $year <= 2027; $year++)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 text-center">
                    <td class="px-4 py-4 border border-gray-200 dark:border-gray-700 text-gray-700 font-bold text-lg">{{ $year }}</td>
                    @for ($month = 1; $month <= 12; $month++)
                        <td class="px-4 py-4 text-center border border-gray-200 dark:border-gray-700">
                            <button class="btn-month inline-flex items-center justify-center px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-700 focus:outline-none" data-year="{{ $year }}" data-month="{{ $month }}">
                                <i class="fa-solid fa-pen-nib"></i>
                            </button>
                        </td>
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>
</div>


@if(Session::has('toastr'))
<script>
    var toastrOptions = {!! Session::get('toastr') !!};
    toastr[toastrOptions.type](toastrOptions.message);
</script>
@endif

@endsection




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function getFirstAndLastDayOfMonth(year, month) {
    const firstDay = new Date(year, month - 1, 2);
    const lastDay = new Date(year, month, 1);

    const formatDay = (date) => date.toISOString().split('T')[0];
    return {
        firstDay: formatDay(firstDay),
        lastDay: formatDay(lastDay)
    };
}

$(document).ready(function() {
    $('.btn-month').click(function() {
        const year = $(this).data('year');
        const month = $(this).data('month');
        const { firstDay, lastDay } = getFirstAndLastDayOfMonth(year, month);
        console.log(firstDay, lastDay);

        $.ajax({
            url: `/v1/transfer-data-diginet/employee-hours`,
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'Authorization': 'Bearer ' + '{{$token}}',
            },
            data: JSON.stringify({
                "FromDate": firstDay,
                "ToDate": lastDay,
                "CompanyCode": "TLCM",
                "WorkplaceCode": "HO"
            }),
            success: function(data) {
                console.log(data);
                // Xử lý dữ liệu trả về từ server tại đây
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });
});
</script>
