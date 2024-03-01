@php
    $beginMonth = 11;
    $beginYear = 2023;
    $currentMonth = (int)date('m');
    $currentYear = (int)date('Y');
@endphp

<meta name="csrf-token" content="{{ csrf_token() }}">
<table class="table-auto w-full text-sm text-left text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
    <thead class="text-xs text-gray-700  bg-gray-50 dark:bg-gray-700 dark:text-gray-400 text-center">
        <tr>
            <th class="px-4 py-3 border border-gray-200 dark:border-gray-700 text-lg">Year</th>
            @for ($month = 1; $month <= 12; $month++)
                @php
                    $abbMonth = App\Utils\Support\DateReport::getMonthAbbreviation($month);
                @endphp
                <th class="px-4 py-3 border border-gray-200 dark:border-gray-700 text-lg">{{$abbMonth}}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @for ($year = $beginYear; $year <= $currentYear; $year++)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 text-center">
                <td class="px-4 py-4 border border-gray-200 dark:border-gray-700 text-gray-700 font-bold text-lg">{{ $year }}</td>
                @for ($month = 1; $month <= 12; $month++)
                    @if($year === $currentYear && $month > $currentMonth || $year === $beginYear && $month < $beginMonth)
                        <td class="px-4 py-4 text-center border border-gray-200 dark:border-gray-700">
                            <button class="opacity-5 cursor-not-allowed  inline-flex items-center justify-center px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-700 focus:outline-none" data-year="{{ $year }}" data-month="{{ $month }}">
                                <i class="fa-solid fa-pen-nib"></i>
                            </button>
                        </td>
                    @else
                        <td class="px-4 py-4 text-center border border-gray-200 dark:border-gray-700">
                            <button class="btn-month inline-flex items-center justify-center px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-700 focus:outline-none" data-year="{{ $year }}" data-month="{{ $month }}">
                                <i class="fa-solid fa-pen-nib"></i>
                            </button>
                        </td>
                    @endif
                @endfor
            </tr>
        @endfor
    </tbody>
</table>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function getFirstAndLastDayOfMonth(year, _month) {
    // Calculate the year and month for the last day
    const yearFisrt = _month === 1 ? year - 1 : year;
    const monthFirst = _month === 1 ? 12 : String(_month - 1).padStart(2, '0');
    // Format the current month to two digits
    const month = String(_month).padStart(2, '0');
    // Define first and last days
    const firstDay = `${yearFisrt}-${monthFirst}-26`;
    const lastDay = `${year}-${month}-25`;
     return {
        firstDay: firstDay,
        lastDay: lastDay
    };
}

$(document).ready(function() {
    $('.btn-month').click(function() {
        const year = $(this).data('year');
        const month = $(this).data('month');
        const { firstDay, lastDay } = getFirstAndLastDayOfMonth(year, month);
        //console.log(firstDay, lastDay);
        let processingToast = toastr.info('Processing your request...', {
                    timeOut: 0, // Prevents the toastr from auto-hiding
                    extendedTimeOut: 0, // Prevents the toastr from auto-hiding when hovered
                    closeButton: true, // Allows the user to close the notification if desired
                    progressBar: true, // Displays a progress bar (though it won't advance automatically)
                    tapToDismiss: false
                });

        $.ajax({
            url: `/v1/transfer-data-diginet/`+ '{{$endpointNameDiginet}}',
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'Authorization': 'Bearer ' + '{{$token}}',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                
            },
            data: JSON.stringify({
                "FromDate": firstDay,
                "ToDate": lastDay,
                "CompanyCode": "TLCM",
                "WorkplaceCode": "HO,TF1,TF2,TF3,NZ,WS"
            }),
            success: function(response) {
                toastr.clear(processingToast);
                if(response.status === 'success') {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
        });
    });
});
</script>
