<div class="mx-auto p-6 rounded-lg shadow-md bg-white-d">
    <div class="mb-6">
        <label for="month" class="block text-sm font-medium text-gray-700">Month:</label>
        <select wire:model.live="month" id="month" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option value="all">All</option>
            @foreach (range(1, 12) as $m)
                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-6">
        <label for="year" class="block text-sm font-medium text-gray-700">Year:</label>
        <select wire:model.live="year" id="year" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option value="all">All</option>
            @foreach (range(date('Y') - 10, date('Y')) as $y)
                <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
        </select>
    </div>

    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Balance Sheet</h2>

        <div class="overflow-x-auto p-4 rounded-lg shadow-inner">
            <table class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white-d divide-y divide-gray-300">
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Reject or Free (Total Purchase Price)</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($rejectOrFreeSum, 2) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Expenses</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($expenseSum, 2) }}</td>
                    </tr>
                    <tr class="bg-white-d font-bold">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Total Loss</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($totalLoss, 2) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Branch Sales (Total Sets)</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($branchSaleSetsSum, 0) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Branch Sales (Total Price)</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($branchSalePriceSum, 2) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Head Office Sales (Total Sets)</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($headOfficeSaleSetsSum, 0) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Head Office Sales (Total Price)</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($headOfficeSalePriceSum, 2) }}</td>
                    </tr>
                    <tr class="bg-white-d font-bold">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Total Revenue</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($totalRevenue, 2) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Total Stamp Sales (Sets)</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($stampSalesSets, 0) }}</td>
                    </tr>
                    <tr class="bg-white-d font-bold">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Net Profit</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($netProfit, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        <button wire:click="exportCSV" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Download CSV</button>
    </div>
</div>
