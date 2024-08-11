<div class="p-6 bg-white rounded-lg shadow-md border border-gray-200">
    <!-- Date and Year Selection -->
    <div class="flex items-center gap-4">
        <div class="mb-4">
            <label for="month" class="block text-sm font-medium text-gray-700">Select Month:</label>
            <select id="month" wire:model.live="selectedMonth" class="p-2 form-select mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-4">
            <label for="year" class="block text-sm font-medium text-gray-700">Select Year:</label>
            <select id="year" wire:model.live="selectedYear" class="p-2 form-select mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @for ($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto mt-6">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-300">Serial Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-300">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-300">Sets</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-300">Set Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-300">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-300">Cash Received</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-300">Due</th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-gray-100 font-bold">
                    <td class="px-6 py-4 border-b border-gray-300" colspan="6"><strong>So far Due:</strong></td>
                    <td class="px-6 py-4 border-b border-gray-300 text-right"><strong> {{ number_format($soFarDue, 2) }}</strong></td>
                </tr>

                @foreach ($completeMonth as $day)
                    @php
                        $sale = $sales->firstWhere('date', $day->format('Y-m-d'));
                        // Check if any of the fields have values
                        $hasValue = !empty($sale['sets']) || !empty($sale['set_price']) || !empty($sale['price']) || !empty($sale['cash']) || !empty($sale['due']);
                    @endphp
                    <tr class="{{ $hasValue ? 'bg-gray-100' : ($loop->odd ? 'bg-white' : '') }} border-b border-gray-300">
                        <td class="px-6 py-4 border-b border-gray-300">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 border-b border-gray-300">{{ $day->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ $sale['sets'] ?? 0 }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($sale['set_price'] ?? 0, 2) }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($sale['price'] ?? 0, 2) }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($sale['cash'] ?? 0, 2) }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($sale['due'] ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            

                <tr class="bg-gray-200 font-bold">
                    <td class="px-6 py-4 border-t border-gray-300 text-right" colspan="3">Total</td>
                    <td class="px-6 py-4 border-t border-gray-300 text-right"> {{ number_format($totalSetPrice, 2) }}</td>
                    <td class="px-6 py-4 border-t border-gray-300 text-right"> {{ number_format($totalPrice, 2) }}</td>
                    <td class="px-6 py-4 border-t border-gray-300 text-right"> {{ number_format($totalCashReceived, 2) }}</td>
                    <td class="px-6 py-4 border-t border-gray-300 text-right"> {{ number_format($totalDue, 2) }}</td>
                </tr>

            </tbody>
        </table>
        <button wire:click="downloadPdf" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Download PDF
        </button>
    </div>
</div>
