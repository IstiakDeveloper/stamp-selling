<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Branch Sales Report</h1>
    @if($fromDate && $toDate)
        <p>Report Period: {{ $fromDate }} to {{ $toDate }}</p>
    @else
        <p>All Data</p>
    @endif
    <table>
        <thead>
            <tr>
                <th>Serial Number</th>
                <th>Branch Name</th>
                <th>Sets</th>
                <th>Price</th>
                <th>Cash Receive</th>
                <th>Total Due</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $data)
            <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data['branch_name'] }}</td>
                    <td>{{ $data['sets'] }}</td>
                    <td>{{ $data['price'] }}</td>
                    <td>{{ $data['cash'] }}</td>
                    <td>{{ $data['total_due'] }}</td>
                </tr>
            @endforeach
            <!-- Total Row -->
            <tr>
                <td colspan="2">Total</td>
                <td>{{ $totalSets }}</td>
                <td>{{ $totalPrice }}</td>
                <td>{{ $totalCash }}</td>
                <td>{{ $totalDue }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
