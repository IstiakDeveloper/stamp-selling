<!DOCTYPE html>
<html>
<head>
    <title>Reject or Free Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f4f4f4; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <h2>Reject or Free Report</h2>

    <div>
        <p><strong>Month:</strong> {{ date('F', mktime(0, 0, 0, $currentMonth, 1)) }} {{ $currentYear }}</p>
        <p><strong>Average Stamp Price per Set:</strong> {{ number_format($averageStampPricePerSet, 2) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Date</th>
                <th>Note</th>
                <th>Sets</th>
                <th>Purchase Price</th>
                <th>Total Price</th>
                <th>Net Loss</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" class="text-right font-bold">Previous Month's Net Loss:</td>
                <td class="text-left font-bold">{{ number_format($previousMonthNetLoss, 2) }}</td>
            </tr>

            @php
                $cumulativeNetLoss = $previousMonthNetLoss;
            @endphp

            @foreach ($rejectOrFreeRecords as $index => $record)
                @php
                    $totalPrice = $record->sets * $averageStampPricePerSet;
                    $cumulativeNetLoss += $totalPrice;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</td>
                    <td>{{ $record->note }}</td>
                    <td>{{ $record->sets }}</td>
                    <td>{{ number_format($averageStampPricePerSet, 2) }}</td>
                    <td>{{ number_format($totalPrice, 2) }}</td>
                    <td>{{ number_format($cumulativeNetLoss, 2) }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="3" class="text-right font-bold">Current Month's Totals:</td>
                <td class="text-left font-bold">{{ number_format($rejectOrFreeRecords->sum('sets'), 0) }}</td>
                <td></td>
                <td></td>
                <td class="text-left font-bold">{{ number_format($cumulativeNetLoss, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
