<!DOCTYPE html>
<html>
<head>
    <title>Stock Register Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        @page {
            margin: 20mm; /* Adjust margins to fit more content */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10px; /* Reduce font size for better fit */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px; /* Reduce padding */
            text-align: right;
        }
        th {
            background-color: #f4f4f4;
        }
        h1, h2, p {
            font-size: 12px; /* Reduce font size for headers */
        }
        .page-break {
            page-break-after: always; /* Page break between sections */
        }
        .text-left {
            text-align: left;
        }
        .font-bold {
            font-weight: bold;
        }
        .bg-gray-800 {
            background-color: #2d2d2d;
        }
        .text-white {
            color: #ffffff;
        }
        .bg-blue-200 {
            background-color: #e3f2fd;
        }
        .bg-yellow-200 {
            background-color: #fdd835;
        }
        .bg-green-200 {
            background-color: #c8e6c9;
        }
    </style>
</head>
<body>
    <h1>Stock Register Report</h1>
    <p><strong>Month:</strong> {{ \Carbon\Carbon::create($selectedYear, $selectedMonth)->format('F Y') }}</p>

    <!-- Combined Stock In and Stock Out Table -->
    <h2>Stock In and Stock Out</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Stock In Sets</th>
                <th>Stock In Price</th>
                <th>Stock In Total Price</th>
                <th>Stock Out Sets</th>
                <th>Stock Out Price</th>
                <th>Stock Out Total Price</th>
                <th>Available Sets</th>
            </tr>
        </thead>
        <tbody>
            <!-- "So Far" Row -->
            @php
                $cumulativeStockIn = $soFarStockIn->sum('sets');
                $cumulativeStockOut = $soFarStockOut->sum('sets');
                $availableSetsSoFar = $cumulativeStockIn - $cumulativeStockOut;
            @endphp
            <tr class="bg-green-200 font-bold">
                <td>Before {{ \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth)->format('F') }}</td>
                <td>{{ number_format($cumulativeStockIn, 2) }}</td>
                <td>-</td>
                <td>{{ number_format($soFarStockIn->sum('total_purchase_price'), 2) }}</td>
                <td>{{ number_format($cumulativeStockOut, 2) }}</td>
                <td>-</td>
                <td>{{ number_format($soFarStockOut->sum('total_price'), 2) }}</td>
                <td>{{ number_format($availableSetsSoFar, 2) }}</td>
            </tr>

            <!-- Stock Data Rows -->
            @foreach ($completeMonth as $day)
                @php
                    $formattedDate = $day->format('Y-m-d');
                    $dayStockInData = $stockInData->firstWhere('date', $formattedDate) ?? ['sets' => 0, 'purchase_price' => 0, 'total_purchase_price' => 0];
                    $dayStockOutData = $stockOutData->get($formattedDate, ['sets' => 0, 'price' => 0, 'total_price' => 0]);

                    // Update cumulative totals
                    $cumulativeStockIn += $dayStockInData['sets'];
                    $cumulativeStockOut += $dayStockOutData['sets'];

                    // Calculate available sets
                    $availableSets = $cumulativeStockIn - $cumulativeStockOut;
                @endphp
                <tr>
                    <td>{{ $day->format('d/m/Y') }}</td>
                    <td>{{ number_format($dayStockInData['sets'], 2) }}</td>
                    <td>{{ number_format($dayStockInData['purchase_price'], 2) }}</td>
                    <td>{{ number_format($dayStockInData['total_purchase_price'], 2) }}</td>
                    <td>{{ number_format($dayStockOutData['sets'], 2) }}</td>
                    <td>{{ number_format($dayStockOutData['price'], 2) }}</td>
                    <td>{{ number_format($dayStockOutData['total_price'], 2) }}</td>
                    <td>{{ number_format($availableSets, 2) }}</td>
                </tr>
            @endforeach

            <!-- This Month Total Row -->
            <tr class="bg-blue-200 font-bold">
                <td>This Month Total:</td>
                <td>{{ number_format($totalStockIn, 2) }}</td>
                <td>-</td>
                <td>{{ number_format($totalStockInPrice, 2) }}</td>
                <td>{{ number_format($totalStockOut, 2) }}</td>
                <td>-</td>
                <td>{{ number_format($totalStockOutPrice, 2) }}</td>
                <td>{{ number_format($availableSets, 2) }}</td>
            </tr>

            <!-- Cumulative Total Row -->
            <tr class="bg-yellow-200 font-bold">
                <td>Total (So Far + This Month):</td>
                <td>{{ number_format($soFarStockIn->sum('sets') + $totalStockIn, 2) }}</td>
                <td>-</td>
                <td>{{ number_format($soFarStockIn->sum('total_purchase_price') + $totalStockInPrice, 2) }}</td>
                <td>{{ number_format($soFarStockOut->sum('sets') + $totalStockOut, 2) }}</td>
                <td>-</td>
                <td>{{ number_format($soFarStockOut->sum('total_price') + $totalStockOutPrice, 2) }}</td>
                <td>{{ number_format($availableSets, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
