<div class="mx-auto p-6 rounded-lg shadow-md bg-white">
    <div class="flex justify-between mb-6">
        <div class="flex gap-4 items-center">
            <div class="mb-6">
                <label for="month" class="block text-sm font-medium text-gray-700">Month:</label>
                <select id="month" wire:model.live="month" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ sprintf('%02d', $m) }}" {{ $m == now()->month ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="year" class="block text-sm font-medium text-gray-700">Year:</label>
                <select id="year" wire:model.live="year" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach(range(Carbon\Carbon::now()->year, 2020) as $year)
                        <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Financial Summary</h2>

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
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">৳{{ number_format($totalCashIn, 2) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Net Profit</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">৳{{ number_format($netProfit, 2) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 h-[3.3rem]"></td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 font-bold">Total</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">৳{{ number_format($totalCashIn + $netProfit, 2) }}</td>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Total Bank or Hand Balance</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">${{ number_format($totalBankOrHandBalance, 2) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">So Far Branch Outstanding</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">৳{{ number_format($outstandingTotal, 2) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Stock Stamp Buy Price</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">৳{{ number_format($stockStampBuyPrice, 2) }}</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 font-bold">Total</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">৳{{ number_format($outstandingTotal + $totalBankOrHandBalance + $stockStampBuyPrice, 2) }}</td>
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
