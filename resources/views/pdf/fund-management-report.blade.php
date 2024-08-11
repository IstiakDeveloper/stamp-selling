<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fund Management Report - {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <h1 class="text-center">Fund Management Report</h1>
    <p class="text-center">Month: {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Note</th>
                <th>Available Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Before {{ \Carbon\Carbon::create($year, $month, 1)->subMonth()->format('F Y') }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ number_format($previousBalance, 2) }}</td>
            </tr>

            @php
                $availableBalance = $previousBalance;
                $index = 2;
            @endphp

            @foreach($data as $entry)
                @php
                    $availableBalance += $entry->type === 'fund_in' ? $entry->amount : -$entry->amount;
                @endphp
                <tr>
                    <td>{{ $index++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($entry->date)->format('Y-m-d') }}</td>
                    <td>{{ number_format($entry->amount, 2) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $entry->type)) }}</td>
                    <td>{{ $entry->note }}</td> <!-- Show note -->
                    <td>{{ number_format($availableBalance, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
