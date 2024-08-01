<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Branch Report</title>
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
            background-color: #f2f2f2;
            text-align: left;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>All Branch Sale Report</h1>
        <p>Previous Outstanding Total: ${{ number_format($previousMonthOutstanding, 2) }}</p>
        <p>Report Date: {{ $monthYear }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Total Sets</th>
                <th>Total Price</th>
                <th>Total Cash Received</th>
                <th>Total Outstanding Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ number_format($reportData['totalSets'], 2) }}</td>
                <td>${{ number_format($reportData['totalPrice'], 2) }}</td>
                <td>${{ number_format($reportData['totalCash'], 2) }}</td>
                <td>${{ number_format($reportData['totalOutstandingBalance'], 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
