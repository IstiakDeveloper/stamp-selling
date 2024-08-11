<div class="mx-auto p-6 rounded-lg shadow-md bg-white">
    <div class="flex justify-between">
        <div class="flex gap-4 items-center">
            <div class="mb-6">
                <label for="month" class="block text-sm font-medium text-gray-700">Month:</label>
                <select id="month" wire:model.live="month" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $i == now()->month ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="mb-6">
                <label for="year" class="block text-sm font-medium text-gray-700">Year:</label>
                <select id="year" wire:model.live="year" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @for ($i = 2020; $i <= date('Y'); $i++)
                        <option value="{{ $i }}" {{ $i == now()->year ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="flex gap-4 items-center mb-6">
            <div>
                <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date:</label>
                <input type="date" id="startDate" wire:model.live="startDate" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="endDate" class="block text-sm font-medium text-gray-700">End Date:</label>
                <input type="date" id="endDate" wire:model.live="endDate" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Expenditure Sheet</h2>

        <div class="flex border-l border-r border-gray-300">
            <!-- Left Column -->
            <div class="w-1/2 border-r border-gray-300">
                <table class="min-w-full divide-y divide-gray-300 border border-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">For the month</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">For the year</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Reject or Free (Total Purchase Price)</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($rejectOrFreeSumMonth, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($rejectOrFreeSumYear, 2) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Expenses</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($expenseSumMonth, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($expenseSumYear, 2) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Sale Set Purchase Price</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($saleStampBuyPriceMonth, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($saleStampBuyPriceYear, 2) }}</td>
                        </tr>
                        <tr class="bg-white font-bold">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Total Loss</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($totalLossMonth, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($totalLossYear, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-4">            <div class="inline-block h-[250px] min-h-[1em] w-0.5 self-stretch bg-gray-500 dark:bg-white/10"></div></div>

            <!-- Right Column -->
            <div class="w-1/2">
                <table class="min-w-full divide-y divide-gray-300 border border-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">For the month</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">For the year</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Starting Net Profit</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($sofarNetProfitSumMonth, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($sofarNetProfitSumYear, 2) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Branch Sales (Total Price)</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($branchSalePriceSumMonth, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($branchSalePriceSumYear, 2) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Head Office Sales (Total Price)</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($headOfficeSalePriceSumMonth, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($headOfficeSalePriceSumYear, 2) }}</td>
                        </tr>
                        
                        <tr class="bg-white font-bold">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Total Sale</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($totalRevenueMonth, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($totalRevenueYear, 2) }}</td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>

        <!-- Net Profit Table -->
        <div class="mt-4">
            <table class="min-w-full divide-y divide-gray-300 border border-gray-300">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">For the month</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">For the year</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-300">
                    <tr class="bg-white font-bold">
                        <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Net Profit</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($netProfitMonth, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">{{ number_format($netProfitYear, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <button wire:click="exportCSV" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Download CSV</button>
            <button wire:click="exportPDF" class="bg-green-500 text-white px-4 py-2 rounded">Export PDF</button>
        </div>
    </div>

</div>
