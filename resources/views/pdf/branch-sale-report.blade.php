<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Sale Report</title>
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
        .branch-top {
            display: flex;
            justify-content: space-between; /* Distributes space between items */
            align-items: flex-start; /* Align items to the start of the flex container */
            margin-bottom: 20px;
        }
        .branch-top > div {
            width: 48%; /* Adjust width to fit the page, keeping space for margins */
        }
    </style>
</head>
<body>
    <h1>Branch Sale Report</h1>
    <div class="branch-top">
        <div>
            <p><strong>Branch:</strong> {{ $branchName }}</p>
            <p>{{ $monthYear }}</p>
            <p>Outstanding Balance for Last Month: BDT: {{ number_format($outstandingBalance, 2) }}</p>
        </div>
    
        <div class="total-summary">
            <p>Total Sets: {{ $totalSets }}</p>
            <p>Total Price: BDT: {{ number_format($totalPrice, 2) }}</p>
            <p>Total Cash Received: BDT: {{ number_format($totalCash, 2) }}</p>
            <p>Total Due: BDT: {{ number_format($totalDue, 2) }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Sets</th>
                <th>Per Set Price</th>
                <th>Total Price</th>
                <th>Cash Received</th>
                <th>Due</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->date }}</td>
                    <td>{{ $sale->sets }}</td>
                    <td>BDT: {{ $sale->per_set_price }}</td>
                    <td>BDT: {{ $sale->total_price }}</td>
                    <td>BDT: {{ $sale->cash }}</td>
                    <td>BDT: {{ $sale->total_price - $sale->cash }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
