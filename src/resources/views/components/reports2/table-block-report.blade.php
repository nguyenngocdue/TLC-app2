 <style>
        /* Table styling */
        table {
            width: 60%; /* Adjust the width as needed */
            margin: 20px auto;
            border-collapse: collapse; /* Ensures borders are merged */
            font-family: Arial, sans-serif; /* Sets a modern font */
        }

        /* Header and cells styling */
        th, td {
            padding: 12px 15px; /* Adds space inside cells */
            border: 1px solid #ccc; /* Gray border for readability */
            text-align: left; /* Aligns text to the left */
        }

        /* Header specific styling */
        th {
            background-color: #4CAF50; /* Green background for headers */
            color: white; /* White text for contrast */
        }

        /* Row hover effect */
        tr:nth-child(even) {background-color: #f2f2f2;} /* Zebra striping */
        tr:hover {background-color: #ddd;} /* Highlight on hover */
    </style>

<x-renderer.heading level=5 xalign='left'>{{$name}}</x-renderer.heading>
<x-renderer.heading level=6 xalign='left'>{{$description}}</x-renderer.heading>

<table>
        <caption>Monthly Sales Data</caption>
        <thead>
            <tr>
                <th>Month</th>
                <th>Sales (USD)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>January</td>
                <td>$10,000</td>
            </tr>
            <tr>
                <td>February</td>
                <td>$12,000</td>
            </tr>
            <tr>
                <td>March</td>
                <td>$9,000</td>
            </tr>
            <tr>
                <td>April</td>
                <td>$14,000</td>
            </tr>
            <tr>
                <td>May</td>
                <td>$18,000</td>
            </tr>
            <tr>
                <td>June</td>
                <td>$16,000</td>
            </tr>
            <tr>
                <td>July</td>
                <td>$15,000</td>
            </tr>
            <tr>
                <td>August</td>
                <td>$10,000</td>
            </tr>
            <tr>
                <td>September</td>
                <td>$20,000</td>
            </tr>
            <tr>
                <td>October</td>
                <td>$17,000</td>
            </tr>
            <tr>
                <td>November</td>
                <td>$13,000</td>
            </tr>
            <tr>
                <td>December</td>
                <td>$15,000</td>
            </tr>
        </tbody>
    </table>