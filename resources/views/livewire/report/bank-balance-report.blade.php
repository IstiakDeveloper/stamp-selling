<div class="mx-auto p-4">
    <!-- Month and Year Selector -->
    <div class="flex items-center mb-6 space-x-4">
        <div class="flex items-center">
            <label for="month" class="mr-2 text-lg font-semibold">Month:</label>
            <select id="month" wire:model.live="month" class="border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="flex items-center">
            <label for="year" class="mr-2 text-lg font-semibold">Year:</label>
            <select id="year" wire:model.live="year" class="border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">
                @for ($i = \Carbon\Carbon::now()->year - 5; $i <= \Carbon\Carbon::now()->year; $i++)
                    <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 bg-white shadow-md rounded-lg">
            <thead class="bg-blue-500 text-white uppercase text-xs font-medium">
                <tr>
                    <th class="px-6 py-3 text-left">SL</th>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left">Cash Receive</th>
                    <th class="px-6 py-3 text-left">Purchase Sets</th>
                    <th class="px-6 py-3 text-left">Purchase Price</th>
                    <th class="px-6 py-3 text-left">Expenses</th>
                    <th class="px-6 py-3 text-left">Available Bank Balance</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                @if (isset($previousMonthData))
                    <tr class="bg-green-200">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900" colspan="2">Previous Month Data</td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ number_format($previousMonthData['cash_receive'] ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ number_format($previousMonthData['purchase_sets'] ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ number_format($previousMonthData['purchase_price'] ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ number_format($previousMonthData['expenses'] ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ number_format(
                                $this->initialBalance
                                + ($previousMonthData['cash_receive'] ?? 0)
                                - ($previousMonthData['purchase_price'] ?? 0)
                                - ($previousMonthData['expenses'] ?? 0),
                                2
                            ) }}
                        </td>
                    </tr>
                @endif
            
                @forelse ($data as $index => $entry)
                    @php
                        // Determine if any of the fields have a value
                        $hasValue = !empty($entry['cash_receive']) || !empty($entry['purchase_sets']) || !empty($entry['purchase_price']) || !empty($entry['expenses']);
                    @endphp
                    <tr class="{{ $hasValue ? 'bg-gray-200' : '' }}">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ $entry['date'] }}</td>
                        <td class="px-6 py-4">
                            {{ number_format($entry['cash_receive'] ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($entry['purchase_sets'] ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($entry['purchase_price'] ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($entry['expenses'] ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($entry['available_balance'] ?? 0, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No data available for the selected month.</td>
                    </tr>
                @endforelse
            </tbody>
            
            <!-- Total Row -->
            <tfoot class="bg-gray-300 text-gray-600 font-semibold">
                <tr>
                    <td colspan="2" class="px-6 py-3 text-left">Total</td>
                    <td class="px-6 py-3 text-left">
                        {{ number_format(array_sum(array_column($data, 'cash_receive')), 2) }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ number_format(array_sum(array_column($data, 'purchase_sets')), 2) }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ number_format(array_sum(array_column($data, 'purchase_price')), 2) }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ number_format(array_sum(array_column($data, 'expenses')), 2) }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        {{ number_format(end($data)['available_balance'] ?? 0, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="mt-6 text-right">
            <button wire:click="downloadPdf" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Download PDF</button>
        </div>
    </div>
</div>
