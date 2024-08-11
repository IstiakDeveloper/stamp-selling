<div class="mx-auto p-6 rounded-lg shadow-md bg-white">
    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Blance Sheet</h2>

        <div class="flex border-l border-r border-gray-300">
            <!-- Left Column -->
            <div class="w-1/2 border-r border-gray-300">
                <table class="min-w-full divide-y divide-gray-300 border border-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Metric</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Funds</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($totalCashIn, 2) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Net Profit</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($netProfit, 2) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 h-[3.3rem]"></td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 font-bold">Total</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">{{ number_format($totalCashIn + $netProfit, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="inline-block w-0.5 self-stretch bg-gray-500"></div>

            <!-- Right Column -->
            <div class="w-1/2">
                <table class="min-w-full divide-y divide-gray-300 border border-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Metric</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Bank Balance</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($totalBankOrHandBalance, 2) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Branches Due</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($outstandingTotal, 2) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Stock</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($stockStampBuyPrice, 2) }}</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 font-bold">Total</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">{{ number_format($outstandingTotal + $totalBankOrHandBalance + $stockStampBuyPrice, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Download Buttons -->
        <div class="mt-4">
            <button wire:click="downloadPdf" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Download PDF</button>
        </div>
    </div>
</div>
