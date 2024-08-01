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
            <div>
                <select id="branch" wire:model="branchId" class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Select Branch</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Generate Report
            </button>
        </div>
    </form>

    <div class="flex justify-between mt-6">
        @if ($outstandingBalance !== null)
            <div>
                <h3 class="text-lg font-medium text-gray-900">Last month outstanding balance was: <span class="text-blue-600">{{ $outstandingBalance }}</span></h3>
            </div>
        @endif    

        @if ($monthNameYear)
            <div>
                <h3 class="text-lg font-medium text-gray-900"><span class="text-blue-600">{{ $monthNameYear }}</span></h3>
            </div>
        @endif
    </div>

    @if ($sales)
        <div class="mt-6 flex justify-between items-center bg-green-600 py-3 px-8 text-white">
            <p class="text-white font-bold text-lg">Total Sets: <span class="">{{ $totalSets }}</span></p>
            <p class="text-white font-bold text-lg">Total Price: <span class="">৳{{ number_format($totalPrice, 2) }}</span></p>
            <p class="text-white font-bold text-lg">Total Cash Received: <span class="">৳{{ number_format($totalCash, 2) }}</span></p>
            <p class="text-white font-bold text-lg">Total Due: <span class="">৳{{ number_format($totalDue, 2) }}</span></p>
        </div>
        <div class="mt-6">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sets</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Per Set Price</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cash Received</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($sales as $sale)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $sale->date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $sale->sets }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"> ৳{{ $sale->per_set_price }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">৳{{ $sale->total_price }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">৳{{ $sale->cash }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">৳{{ $sale->total_price - $sale->cash }}</td>
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
