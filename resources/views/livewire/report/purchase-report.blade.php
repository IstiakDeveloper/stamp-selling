<div class="p-6 bg-white rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-4">
            <div>
                <label for="selectedYear" class="block text-sm font-medium text-gray-700">Year</label>
                <select id="selectedYear" wire:model="selectedYear" wire:change="handleFilterChange" class="form-select mt-1 block w-full">
                    <option value="all">All Year</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="selectedMonth" class="block text-sm font-medium text-gray-700">Month</label>
                <select id="selectedMonth" wire:model="selectedMonth" wire:change="handleFilterChange" class="form-select mt-1 block w-full">
                    <option value="all">All Month</option>
                    @foreach($months as $monthNumber => $monthName)
                        <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <button wire:click="downloadPdf" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">Download PDF</button>
        </div>
    </div>

    <div class="mb-4">
        <h3 class="text-lg font-medium text-gray-700">Opening Stock: {{ number_format($openingStock, 2) }}</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sets</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pieces</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price Per Set</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($stocks as $stock)
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $stock->date }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $stock->address }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $stock->sets }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $stock->pieces }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $stock->price_per_set }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $stock->total_price }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
