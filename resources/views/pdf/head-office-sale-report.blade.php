<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Head Office Sale Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Head Office Sale Report</h1>
    <p>Report for: {{ $monthYear }}</p>
    <p>Total Sets: {{ $totalSets }}</p>
    <p>Total Price: ${{ number_format($totalPrice, 2) }}</p>
    <p>Total Cash Received: ${{ number_format($totalCash, 2) }}</p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Sets</th>
                <th>Per Set Price</th>
                <th>Total Price</th>
                <th>Cash Received</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->date }}</td>
                    <td>{{ $sale->sets }}</td>
                    <td>{{ $sale->per_set_price }}</td>
                    <td>{{ $sale->total_price }}</td>
                    <td>{{ $sale->cash }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
