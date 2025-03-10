@php
    $beginMonth = 5;
    $beginYear = 2024;
    $currentMonth = (int) date('m');
    $currentYear = (int) date('Y') + 1;

    $currentDay = (int) date('d');
    if ($currentDay > 25 && $currentDay <= 31) {
        // Check if the current month is December
        if ($currentMonth == 12) {
            // If it's December, set the month to January (1) of the next year
            $currentMonth = 1;
        } else {
            // Otherwise, increment the month by 1
            $currentMonth += 1;
        }
    }

@endphp

<div class='pt-4'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <table
        class="table-auto w-full text-smp text-left text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
        <thead class="text-xs text-gray-700  bg-gray-50 dark:bg-gray-700 dark:text-gray-400 text-center">
            <tr>
                <th class="px-4 py-3 border border-gray-200 dark:border-gray-700 text-lg">Year</th>
                @for ($month = 1; $month <= 12; $month++)
                    @php
                        $abbMonth = App\Utils\Support\DateReport::getMonthAbbreviation($month);
                    @endphp
                    <th class="px-4 py-3 border border-gray-200 dark:border-gray-700 text-lg">{{ $abbMonth }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @for ($year = $beginYear; $year <= $currentYear; $year++)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 text-center">
                    <td class="px-4 py-4 border border-gray-200 dark:border-gray-700 text-gray-700 font-bold text-lg">
                        {{ $year }}</td>
                    @for ($month = 1; $month <= 12; $month++)
                        @if (($year === $currentYear && $month > $currentMonth) || ($year === $beginYear && $month < $beginMonth))
                            <td class="px-4 py-4 text-center border border-gray-200 dark:border-gray-700">
                                <button
                                    class="opacity-5 cursor-not-allowed  inline-flex items-center justify-center px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-700 focus:outline-none">
                                    <i class="fa-solid fa-pen-nib"></i>
                                </button>
                            </td>
                        @else
                            <td class="px-4 py-4 text-center border border-gray-200 dark:border-gray-700">
                                <button
                                    class="btn-month inline-flex items-center justify-center px-4 py-2 text-white bg-green-500 rounded hover:bg-blue-700 focus:outline-none"
                                    data-year="{{ $year }}"
                                    data-month="{{ $month }}
                                            ">
                                    <i class="fa-solid fa-pen-nib"></i>
                                </button>
                            </td>
                        @endif
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('.btn-month').click(function() {
            const year = parseInt($(this).data('year'));
            const month = parseInt($(this).data('month'));
            const {
                firstDay,
                lastDay
            } = getFirstAndLastDayOfMonth(year, month);
            const urlAPI = '{{ $endpointNameDiginet }}' === 'all-tables' ?
                `/v1/transfer-data-diginet/update-all-tables` :
                `/v1/transfer-data-diginet/` + '{{ $endpointNameDiginet }}';

            let processingToast = toastr.info('Processing your request...', {
                timeOut: 0,
                extendedTimeOut: 0,
                closeButton: true,
                progressBar: true,
                tapToDismiss: true,
            });

            $.ajax({
                url: urlAPI,
                method: "POST",
                contentType: 'application/json',
                headers: {
                    'Authorization': 'Bearer ' + '{{ $token }}',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                },
                data: JSON.stringify({
                    "FromDate": firstDay,
                    "ToDate": lastDay,
                    "CompanyCode": "TLCM,TLCE",
                    "WorkplaceCode": "HO,TF1,TF2,TF3,NZ,1HO,1TF1,1TF2,1TF3,1NZ"
                }),
                success: function(response) {
                    if (Array.isArray(response)) {
                        response.map((item) => {
                            if (item.result && item.result.original !== undefined) {
                                responseOnItems = item.result.original;
                                if (Array.isArray(responseOnItems)) {
                                    responseOnItems.map((item) => {
                                        toastr.clear(processingToast);
                                        if (item.status === 'success') {
                                            toastr.success(item.message);
                                        } else {
                                            toastr.warning(item.message);
                                        }
                                    })
                                }
                            } else {
                                toastr.clear(processingToast);
                                if (item.status === 'success') {
                                    toastr.success(item.message);
                                } else {
                                    toastr.warning(item.message);
                                }
                            }

                        })
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr, status, error)
                    toastr.clear(processingToast);
                    toastr.error('An error occurred: ' + xhr.responseJSON.message);
                }
            });
        });
    });
</script>
