<div class="mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Branch Management</h1>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
        <form wire:submit.prevent="store" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <div class="mb-4">
                    <label for="branch_code" class="block text-sm font-medium text-gray-700">Branch Code</label>
                    <input type="text" id="branch_code" wire:model.defer="branch_code" class="mt-1 p-2 border rounded-md w-full">
                    @error('branch_code') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
    
                <div class="mb-2">
                    <label for="branch_name" class="block text-sm font-medium text-gray-700">Branch Name</label>
                    <input type="text" id="branch_name" wire:model.defer="branch_name" class="mt-1 p-2 border rounded-md w-full">
                    @error('branch_name') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <div class="mb-4">
                    <label for="outstanding_balance" class="block text-sm font-medium text-gray-700">Outstanding Balance</label>
                    <input type="number" step="0.01" id="outstanding_balance" wire:model.defer="outstanding_balance" class="mt-1 p-2 border rounded-md w-full">
                    @error('outstanding_balance') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <div class="mb-4">
                    <button type="submit" class="bg-blue-500 text-white p-2 rounded-md">Save Branch</button>
                </div>
            </div>
        </form>

        <div class="mb-4">
            <h2 class="text-xl font-bold mb-2">Branches List</h2>
            <table class="min-w-full border border-gray-300">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b text-left">Branch Code</th>
                        <th class="py-2 px-4 border-b">Branch Name</th>
                        <th class="py-2 px-4 border-b">Outstanding Balance</th>
                        <th class="py-2 px-4 border-b text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branches as $branch)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $branch->branch_code }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $branch->branch_name }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ number_format($branch->outstanding_balance, 2) }}</td>
                        <td class="py-2 px-4 border-b text-right">
                            <button wire:click="edit({{ $branch->id }})" class="bg-yellow-500 text-white p-1 rounded-md">Edit</button>
                            <button wire:click="delete({{ $branch->id }})" class="bg-red-500 text-white p-1 rounded-md">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
