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

    @if ($monthNameYear)
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900"><span class="text-blue-600">{{ $monthNameYear }}</span></h3>
        </div>
    @endif

    @if ($expenses)
        <div class="mt-6">
            <p>Total Amount: <span class="text-blue-600">৳{{ number_format($totalAmount, 2) }}</span></p>
            <table class="min-w-full bg-white border border-gray-200 mt-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($expenses as $expense)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $expense->date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">৳{{ $expense->amount }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $expense->purpose }}</td>
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
