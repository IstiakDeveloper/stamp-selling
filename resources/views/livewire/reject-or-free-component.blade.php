<div class="mx-auto p-6 rounded">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="saveTransaction">
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700">Date:</label>
            <input type="date" wire:model.live="date" id="date" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('date') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="sets" class="block text-sm font-medium text-gray-700">Sets:</label>
            <input type="number" step="0.001" wire:model.live="sets" id="sets" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('sets') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="purchase_price_per_set" class="block text-sm font-medium text-gray-700">Purchase Price Per Set:</label>
            <input type="number" step="any" wire:model.live="purchase_price_per_set" id="purchase_price_per_set" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" readonly>
            @error('purchase_price_per_set') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="purchase_price_total" class="block text-sm font-medium text-gray-700">Purchase Price Total:</label>
            <input type="number" step="any" wire:model.live="purchase_price_total" id="purchase_price_total" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" readonly>
            @error('purchase_price_total') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="note" class="block text-sm font-medium text-gray-700">Note:</label>
            <textarea wire:model.live="note" id="note" rows="3" class="form-textarea mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            @error('note') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save Transaction</button>
        </div>
    </form>

    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Reject or Free Transactions</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sets</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Price Per Set</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reject Price Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                    </tr>
                </thead>
                <tbody class="bg-white-d divide-y divide-gray-200">
                    @foreach ($rejectOrFrees as $rejectOrFree)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $rejectOrFree->date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $rejectOrFree->sets }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $rejectOrFree->purchase_price_per_set }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $rejectOrFree->purchase_price_total }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $rejectOrFree->note }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
