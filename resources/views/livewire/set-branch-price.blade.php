<div class="mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Set Branch Per Stamp Price</h1>

    <form wire:submit.prevent="savePrice">
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
            <input type="number" step="0.01" id="price" wire:model.defer="price" class="mt-1 p-2 border rounded-md w-full">
            @error('price') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <button type="submit" class="bg-blue-500 text-white p-2 rounded-md">Save Price</button>
        </div>
    </form>

    @if (session()->has('message'))
        <div class="bg-green-500 text-white p-2 rounded-md">
            {{ session('message') }}
        </div>
    @endif
</div>
