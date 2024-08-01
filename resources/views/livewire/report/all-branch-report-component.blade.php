<div>
    <div class="mb-4">
        <label for="branch" class="block text-sm font-medium text-gray-700">Branch</label>
        <select id="branch" wire:model.live="branch_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            <option value="">All Branches</option>
            @foreach($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
        <select id="month" wire:model.live="month" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            @foreach(range(1, 12) as $m)
                <option value="{{ sprintf('%02d', $m) }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
        <select id="year" wire:model.live="year" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            @foreach(range(Carbon\Carbon::now()->year, 2020) as $year)
                <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="mb-4">
        <p class="text-lg font-medium">Previous Outstanding Total: ${{ number_format($previousMonthOutstanding, 2) }}</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sets</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Cash Received</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Outstanding Balance</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($reportData['totalSets'], 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${{ number_format($reportData['totalPrice'], 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${{ number_format($reportData['totalCash'], 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${{ number_format($reportData['totalOutstandingBalance'], 2) }}</td>
                </tr>
            </tbody>
        </table>
        <div class="mb-4">
            <button wire:click="downloadPdf" class="inline-block mt-2 px-4 py-2 text-white bg-blue-500 hover:bg-blue-700 rounded">
                Download PDF
            </button>
        </div>
    </div>
</div>
