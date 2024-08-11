<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Head Office Sale Report</title>
    <style>
        /* Compact styles for A4 page */
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px; /* Reduced font size */
            margin: 10px; /* Reduced margin */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px; /* Reduced table font size */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px; /* Reduced padding */
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2 style="font-size: 12px;">Head Office Sale Report - {{ date('F Y', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)) }}</h2>

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Date</th>
                <th>Sets</th>
                <th>Set Price</th>
                <th>Price</th>
                <th>Cash Received</th>
                <th>Due</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6">So far Due:</td>
                <td> {{ number_format($soFarDue, 2) }}</td>
            </tr>

            @foreach ($completeMonth as $day)
                @php
                    $sale = $sales->firstWhere('date', $day->format('Y-m-d'));
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $day->format('Y-m-d') }}</td>
                    <td>{{ $sale['sets'] ?? 0 }}</td>
                    <td> {{ number_format($sale['set_price'] ?? 0, 2) }}</td>
                    <td> {{ number_format($sale['price'] ?? 0, 2) }}</td>
                    <td> {{ number_format($sale['cash'] ?? 0, 2) }}</td>
                    <td> {{ number_format($sale['due'] ?? 0, 2) }}</td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td colspan="3">Total</td>
                <td> {{ number_format($totalSetPrice, 2) }}</td>
                <td> {{ number_format($totalPrice, 2) }}</td>
                <td> {{ number_format($totalCashReceived, 2) }}</td>
                <td> {{ number_format($totalDue, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
