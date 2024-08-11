<div class="p-6 bg-white rounded-lg shadow-md border border-gray-200">
    <div class="flex items-center gap-4 mb-6">
        <div class="mb-4">
            <label for="fromDate" class="block text-sm font-medium text-gray-700">From Date:</label>
            <input type="date" id="fromDate" wire:model.live="fromDate" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="toDate" class="block text-sm font-medium text-gray-700">To Date:</label>
            <input type="date" id="toDate" wire:model.live="toDate" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-300">Serial Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-300">Branch Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-300">Sets</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-300">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-300">Cash Receive</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-300">Total Due</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $data)
                    <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} border-b border-gray-200">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $data['branch_name'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $data['sets'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $data['price'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $data['cash'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $data['total_due'] }}</td>
                    </tr>
                @empty
                    <!-- Show this row if no data is available for the selected period but display overall totals -->
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            No sales data available for the selected period. Showing all data.
                        </td>
                    </tr>
                @endforelse

                <!-- Total Row -->
                <tr class="bg-gray-200 font-bold border-t border-gray-300">
                    <td class="px-6 py-4 text-sm text-right" colspan="2">Total:</td>
                    <td class="px-6 py-4 text-sm text-center">{{ $totalSets }}</td>
                    <td class="px-6 py-4 text-sm text-center">{{ $totalPrice }}</td>
                    <td class="px-6 py-4 text-sm text-center">{{ $totalCash }}</td>
                    <td class="px-6 py-4 text-sm text-center">{{ $totalDue }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Button to download PDF -->
    <div class="mt-4 text-right">
        <button wire:click="downloadPdf" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Download PDF</button>
    </div>
</div>
