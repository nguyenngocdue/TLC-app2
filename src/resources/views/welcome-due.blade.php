@extends('layouts.app')
@section('content')

<div class="w-[300px] overflow-x-auto relative">
    <table class="min-w-full">
        <thead>
            <tr>
                <th class=" table-th-fixed-left table-th-fixed-left-5 table-th-fixed-left table-th-fixed-left-5 bg-gray-100 px-4 py-3 border-b border-gray-300 border1 border-r   px-6 py-3 z-10 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider  sticky left-0">
                    Column 1
                </th>
                <th class="table-th-fixed-left table-th-fixed-left-5 table-th-fixed-left table-th-fixed-left-5 bg-gray-100 px-4 py-3 border-b border-gray-300 border1 border-r   px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider  ">
                    Column 2
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider fixed">
                    Column 3
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Column 4
                </th>
                <!-- Add more column headers here -->
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="px-6 py-3 z-10 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider sticky left-0">
                    Data 1
                </td>
                <td class="px-6 py-3 z-10 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider sticky left-0">
                    Data 2
                </td>
                <td class="px-6 py-4 z-0 whitespace-no-wrap border-b border-gray-200">
                    Data 3
                </td>
                <td class="px-6 py-4 z-0 whitespace-no-wrap border-b border-gray-200">
                    Data 4
                </td>

                <!-- Add more data cells here -->
            </tr>
            <!-- Add more rows of data here -->
        </tbody>
    </table>
</div>

@endsection
