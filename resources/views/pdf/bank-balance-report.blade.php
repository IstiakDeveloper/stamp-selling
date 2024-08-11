<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .container { width: 100%; padding: 20px; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th, td { border: 1px solid #dddddd; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total-row { background-color: #f9f9f9; font-weight: bold; }
        h1, h2 { font-size: 12px; }
        p { font-size: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bank Balance Report</h1>
        <p><strong>Month:</strong> {{ $month }}</p>
        <p><strong>Year:</strong> {{ $year }}</p>

        <!-- Data Table -->
        <table>
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Date</th>
                    <th>Cash Receive</th>
                    <th>Purchase Sets</th>
                    <th>Purchase Price</th>
                    <th>Expenses</th>
                    <th>Available Bank Balance</th>
                </tr>
            </thead>
            <tbody>
                <!-- Previous Month Data as the first row -->
                @if (isset($previousMonthData))
                    <tr>
                        <td colspan="2"><strong>Previous Month Data</strong></td>
                        <td>{{ number_format($previousMonthData['cash_receive'], 2) }}</td>
                        <td>{{ number_format($previousMonthData['purchase_sets'], 2) }}</td>
                        <td>{{ number_format($previousMonthData['purchase_price'], 2) }}</td>
                        <td>{{ number_format($previousMonthData['expenses'], 2) }}</td>
                        <td>{{ number_format($previousMonthData['balance'], 2) }}</td>
                    </tr>
                @endif

                <!-- Current Month Data -->
                @forelse ($data as $index => $entry)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $entry['date'] }}</td>
                        <td>{{ number_format($entry['cash_receive'], 2) }}</td>
                        <td>{{ number_format($entry['purchase_sets'], 2) }}</td>
                        <td>{{ number_format($entry['purchase_price'], 2) }}</td>
                        <td>{{ number_format($entry['expenses'], 2) }}</td>
                        <td>{{ number_format($entry['available_balance'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No data available for the selected month.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2">Total</td>
                    <td>{{ number_format(array_sum(array_column($data, 'cash_receive')), 2) }}</td>
                    <td>{{ number_format(array_sum(array_column($data, 'purchase_sets')), 2) }}</td>
                    <td>{{ number_format(array_sum(array_column($data, 'purchase_price')), 2) }}</td>
                    <td>{{ number_format(array_sum(array_column($data, 'expenses')), 2) }}</td>
                    <td>{{ number_format(end($data)['available_balance'] ?? 0, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
