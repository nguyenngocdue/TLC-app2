@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Combobox</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto p-6 bg-white rounded shadow-lg">
        <button id="addComboboxBtn" class="mb-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Plus
        </button>
        <div id="comboboxContainer" class="space-y-4"></div>
    </div>

    <script>
        document.getElementById('addComboboxBtn').addEventListener('click', function() {
            // Create a new div to hold the combobox
            const newDiv = document.createElement('div');
            newDiv.className = 'combobox';

            // Create a new combobox (select element)
            const newSelect = document.createElement('select');
            newSelect.className = 'combobox-select block w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500';

            // Add options to the combobox
            const option1 = document.createElement('option');
            option1.value = 'option1';
            option1.text = 'Option 1';
            newSelect.appendChild(option1);

            const option2 = document.createElement('option');
            option2.value = 'option2';
            option2.text = 'Option 2';
            newSelect.appendChild(option2);

            // Append the combobox to the div
            newDiv.appendChild(newSelect);

            // Append the div to the container
            document.getElementById('comboboxContainer').appendChild(newDiv);
        });
    </script>
</body>
</html>

@endsection
