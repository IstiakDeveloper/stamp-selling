<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #eaeaea;
        }
        .bold-row {
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .table-container {
            display: flex;
        }
        .table-container .table {
            width: 100%;
            border-right: 1px solid #ddd;
        }
        .table-container .table:last-child {
            border-right: none;
        }
        .table-container .table td, .table-container .table th {
            border-right: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Balance Sheet</h1>

    <p>Selected Year: {{ $year }}</p>
    <p>Selected Month: {{ $month }}</p>

    <!-- Table with two columns -->
    <div class="table-container">
        <!-- Left Column -->
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>For the Month</th>
                    <th>For the Year</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Reject or Free (Total Purchase Price)</td>
                    <td class="text-right">BDT: {{ number_format($rejectOrFreeSumMonth, 2) }}</td>
                    <td class="text-right">BDT: {{ number_format($rejectOrFreeSumYear, 2) }}</td>
                </tr>
                <tr>
                    <td>Expenses</td>
                    <td class="text-right">BDT: {{ number_format($expenseSumMonth, 2) }}</td>
                    <td class="text-right">BDT: {{ number_format($expenseSumYear, 2) }}</td>
                </tr>
                <tr>
                    <td>Sale Set Purchase Price</td>
                    <td class="text-right">BDT: {{ number_format($saleStampBuyPriceMonth, 2) }}</td>
                    <td class="text-right">BDT: {{ number_format($saleStampBuyPriceYear, 2) }}</td>
                </tr>
                <tr class="bold-row">
                    <td>Total Loss</td>
                    <td class="text-right">BDT: {{ number_format($totalLossMonth, 2) }}</td>
                    <td class="text-right">BDT: {{ number_format($totalLossYear, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Right Column -->
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>For the Month Amount</th>
                    <th>For the Year Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Branch Sales (Total Price)</td>
                    <td class="text-right">BDT: {{ number_format($branchSalePriceSumMonth, 2) }}</td>
                    <td class="text-right">BDT: {{ number_format($branchSalePriceSumYear, 2) }}</td>
                </tr>
                <tr>
                    <td>Head Office Sales (Total Price)</td>
                    <td class="text-right">BDT: {{ number_format($headOfficeSalePriceSumMonth, 2) }}</td>
                    <td class="text-right">BDT: {{ number_format($headOfficeSalePriceSumYear, 2) }}</td>
                </tr>
                <tr class="bold-row">
                    <td>Total Sale</td>
                    <td class="text-right">BDT: {{ number_format($totalRevenueMonth, 2) }}</td>
                    <td class="text-right">BDT: {{ number_format($totalRevenueYear, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Net Profit Table -->
    <h2>Net Profit</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>For the Month Amount</th>
                <th>For the Year Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bold-row">
                <td>Net Profit</td>
                <td class="text-right">BDT: {{ number_format($netProfitMonth, 2) }}</td>
                <td class="text-right">BDT: {{ number_format($netProfitYear, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
