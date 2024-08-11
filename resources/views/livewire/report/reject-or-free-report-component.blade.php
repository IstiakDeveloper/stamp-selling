<div class="mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">Reject or Free Report</h2>

    <div class="flex gap-4 mb-4">
        <div>
            <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
            <select id="month" wire:model.live="currentMonth" class="mt-1 p-2 border rounded-md shadow-sm">
                @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
            <select id="year" wire:model.live="currentYear" class="mt-1 p-2 border rounded-md shadow-sm">
                @foreach(range(now()->year - 5, now()->year) as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>

        <div class="ml-auto flex items-center">
            <button wire:click="downloadPDF" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Download PDF</button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-xs font-medium">
                    <th class="py-4 px-6 text-left">SL</th>
                    <th class="py-4 px-6 text-left">Date</th>
                    <th class="py-4 px-6 text-left">Note</th>
                    <th class="py-4 px-6 text-left">Sets</th>
                    <th class="py-4 px-6 text-left">Purchase Price</th>
                    <th class="py-4 px-6 text-left">Total Price</th>
                    <th class="py-4 px-6 text-left">Net Loss</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                    <td class="text-right py-3 px-6 font-bold" colspan="6">Previous Net Loss:</td>
                    <td class="text-left py-3 px-6" >{{ number_format($previousMonthNetLoss, 2) }}</td>
                </tr>
        
                @php
                    $cumulativeNetLoss = $previousMonthNetLoss;
                    $totalSetsThisMonth = 0;
                @endphp
        
                @foreach ($rejectOrFreeRecords as $index => $record)
                    @php
                        $totalPrice = $record->sets * $averageStampPricePerSet;
                        $cumulativeNetLoss += $totalPrice;
                        $totalSetsThisMonth += $record->sets;
                    @endphp
                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-6 text-left">{{ $index + 1 }}</td>
                        <td class="py-3 px-6 text-left">{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</td>
                        <td class="py-3 px-6 text-left">{{ $record->note }}</td>
                        <td class="py-3 px-6 text-left">{{ $record->sets }}</td>
                        <td class="py-3 px-6 text-left">{{ number_format($averageStampPricePerSet, 2) }}</td>
                        <td class="py-3 px-6 text-left">{{ number_format($totalPrice, 2) }}</td>
                        <td class="py-3 px-6 text-left">{{ number_format($cumulativeNetLoss, 2) }}</td>
                    </tr>
                @endforeach
        
                <tr class="bg-gray-50 font-semibold">
                    <td colspan="3" class="text-right py-3 px-6 font-bold">Current Month's Totals:</td>
                    <td class="text-left py-3 px-6">{{ number_format($totalSetsThisMonth, 0) }}</td>
                    <td></td>
                    <td></td>
                    <td class="text-left pl-6">{{ number_format($cumulativeNetLoss, 2) }}</td>
                </tr>
            </tbody>
        </table>        
    </div>
</div>
