<div class="mx-auto p-6 rounded">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="saveSale">
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700">Date:</label>
            <input type="date" wire:model.live="date" id="date" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('date') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="branch_id" class="block text-sm font-medium text-gray-700">Branch:</label>
            <select wire:model.live="branch_id" id="branch_id" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Select Branch</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                @endforeach
            </select>
            @error('branch_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="sets" class="block text-sm font-medium text-gray-700">Sets:</label>
            <input type="number" wire:model.live="sets" id="sets" step="any" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('sets') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="per_set_price" class="block text-sm font-medium text-gray-700">Per Set Price:</label>
            <input type="text" wire:model.live="perSetPrice" id="per_set_price" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" readonly>
        </div>

        <div class="mb-4">
            <label for="total_price" class="block text-sm font-medium text-gray-700">Total Price:</label>
            <span class="text-lg font-semibold">
                @if (!is_null($totalPrice))
                    ${{ number_format($totalPrice, 2) }}
                @endif
            </span>
        </div>

        <div class="mb-4">
            <label for="cash" class="block text-sm font-medium text-gray-700">Cash Received:</label>
            <input type="text" wire:model.live="cash" id="cash" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('cash') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>
        
        <div class="text-sm text-gray-700 mb-2">
            @if ($cash !== null && is_numeric($cash))
                @if ($extraMoney !== null)
                    @if ($extraMoney > 0)
                        <span class="text-green-500">Extra Money: +৳ {{ number_format($extraMoney, 2) }}</span>
                    @elseif ($extraMoney < 0)
                        <span class="text-red-500">Due Amount: -৳ {{ number_format(abs($extraMoney), 2) }}</span>
                    @else
                        <span class="text-gray-500">Exact Amount Received</span>
                    @endif
                @endif
            @endif
        </div>
        

        <div class="mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save Sale</button>
        </div>
    </form>

    <div class="mt-8">
        <h2 class="text-lg font-semibold mb-4">Transaction List</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white-d border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Sets</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Per Set Price</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Cash Received</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($branchSales as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $transaction->date }}</td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $transaction->sets }}</td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $transaction->per_set_price }}</td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $transaction->total_price }}</td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $transaction->cash }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
