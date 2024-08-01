<div class="p-6 bg-white shadow-lg rounded-lg">
    <form wire:submit.prevent="generateReport" class="space-y-4">
        <div class="flex gap-4 items-center">
            <div>
                <select id="year" wire:model="year" class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach(range(date('Y'), date('Y') - 10) as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select id="month" wire:model="month" class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Generate Report
            </button>
        </div>
    </form>

    @if ($rejectOrFreeData)
        <div class="mt-6">
            <div class="mt-6 flex justify-between items-center bg-green-600 py-3 px-8">
                <p class="text-white font-bold text-lg">Total Sets: <span>{{ $totalSets }}</span></p>
                <p class="text-white font-bold text-lg">Total Purchase Price: <span>৳{{ number_format($totalPurchasePrice, 2) }}</span></p>
            </div>
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sets</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Price Per Set</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Purchase Price</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($rejectOrFreeData as $data)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $data->date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $data->sets }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">৳{{ $data->purchase_price_per_set }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">৳{{ $data->purchase_price_total }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $data->note }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button wire:click="downloadPdf" class="mt-4 bg-green-600 text-white py-2 px-4 rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Download PDF
            </button>
        </div>
    @endif
</div>
