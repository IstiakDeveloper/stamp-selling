<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reject or Free Report</title>
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
        .total-summary {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Reject or Free Report</h1>
    <p>{{ $monthYear }}</p>

    <div class="total-summary">
        <p>Total Sets: <span class="text-blue-600">{{ $totalSets }}</span></p>
        <p>Total Purchase Price: <span class="text-blue-600">${{ number_format($totalPurchasePrice, 2) }}</span></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Sets</th>
                <th>Purchase Price Per Set</th>
                <th>Total Purchase Price</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rejectOrFreeData as $data)
                <tr>
                    <td>{{ $data->date }}</td>
                    <td>{{ $data->sets }}</td>
                    <td>{{ $data->purchase_price_per_set }}</td>
                    <td>{{ $data->purchase_price_total }}</td>
                    <td>{{ $data->note }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
