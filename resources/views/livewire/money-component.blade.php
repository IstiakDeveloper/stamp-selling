<div>
    <div class="mb-4">
        <p class="text-lg font-semibold">Total Balance: ৳{{ number_format($totalBalance, 2) }}</p>
    </div>

    <form wire:submit.prevent="saveTransaction" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" id="date" wire:model.live="date" class="mt-1 p-2 border rounded-md w-full">
                @error('date') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                <input type="text" id="amount" wire:model.defer="amount" class="mt-1 p-2 border rounded-md w-full">
                @error('amount') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

        </div>

        <div>
            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <select id="type" wire:model.live="type" class="mt-1 p-2 border rounded-md w-full">
                    <option>Select In or Out</option>
                    <option value="cash_in">Cash In</option>
                    <option value="cash_out">Cash Out</option>
                </select>
                @error('type') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="details" class="block text-sm font-medium text-gray-700">Details</label>
                <input type="text" id="details" wire:model.defer="details" class="mt-1 p-2 border rounded-md w-full">
                @error('details') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
     
        </div>

        <div>
            <div class="mb-4">
                <button type="submit" class="bg-blue-500 text-white p-2 rounded-md w-full">Save Transaction</button>
            </div>
        </div>
    </form>

    <hr class="my-6">

    <div class="mt-4">
        <h2 class="text-lg font-semibold">Transaction List</h2>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($transactions as $transaction)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->date }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">৳{{ number_format($transaction->amount, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->details }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
