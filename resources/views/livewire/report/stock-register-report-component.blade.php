<div class="p-6">
    <!-- Date Selection -->
    <div class="mb-6">
        <label for="month" class="mr-2 font-bold">Select Month:</label>
        <select wire:model.live="selectedMonth" id="month" class="border rounded p-2">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}</option>
            @endfor
        </select>

        <label for="year" class="ml-4 mr-2 font-bold">Select Year:</label>
        <select wire:model.live="selectedYear" id="year" class="border rounded p-2">
            @for ($y = \Carbon\Carbon::now()->year; $y >= 2000; $y--)
                <option value="{{ $y }}">{{ $y }}</option>
            @endfor
        </select>
    </div>

    <!-- Flexbox Container for Tables -->
    <div class="flex gap-6">
        <!-- Stock In Table -->
        <div class="flex-1 bg-white rounded-lg shadow-md p-4">
            <h3 class="text-xl font-bold mb-2">Stock In</h3>
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="w-1/3 px-6 py-3 text-left">Date</th>
                        <th class="w-1/3 px-6 py-3 text-right">Sets</th>
                        <th class="w-1/3 px-6 py-3 text-right">Price</th>
                        <th class="w-1/3 px-6 py-3 text-right">Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Stock In So Far -->
                    <tr class="bg-green-200 font-bold">
                        <td class="px-6 py-4 border-b border-gray-300" colspan="2">So Far (Before {{ \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth)->format('F') }}):</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($soFarStockIn->sum('sets'), 2) }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($soFarStockIn->sum('total_purchase_price'), 2) }}</td>
                    </tr>

                    <!-- Stock In Data Rows -->
                    @foreach ($completeMonth as $day)
                        @php
                            $dayData = $stockInData->firstWhere('date', $day->format('Y-m-d')) ?? ['sets' => 0, 'purchase_price' => 0, 'total_purchase_price' => 0];
                            $isDataAvailable = $dayData['sets'] > 0 || $dayData['purchase_price'] > 0 || $dayData['total_purchase_price'] > 0;
                        @endphp
                        <tr class="{{ $isDataAvailable ? 'bg-gray-200' : '' }}">
                            <td class="px-6 py-4 border-b border-gray-300">{{ $day->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($dayData['sets'], 2) }}</td>
                            <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($dayData['purchase_price'], 2) }}</td>
                            <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($dayData['total_purchase_price'], 2) }}</td>
                        </tr>
                    @endforeach

                    <!-- Stock In This Month Total -->
                    <tr class="bg-blue-200 font-bold">
                        <td class="px-6 py-4 border-b border-gray-300" colspan="2">This Month Total:</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($totalStockIn, 2) }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($totalStockInPrice, 2) }}</td>
                    </tr>

                    <!-- Cumulative Stock In Total (So Far + This Month) -->
                    <tr class="bg-yellow-200 font-bold">
                        <td class="px-6 py-4 border-b border-gray-300" colspan="2">Total:</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($totalStockIn + $soFarStockIn->sum('sets'), 2) }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($totalStockInPrice + $soFarStockIn->sum('total_purchase_price'), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Stock Out Table -->
        <div class="flex-1 bg-white rounded-lg shadow-md p-4">
            <h3 class="text-xl font-bold mb-2">Sold Out or Reject Free</h3>
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="w-1/4 px-6 py-3 text-left">Date</th>
                        <th class="w-1/4 px-6 py-3 text-right">Sets</th>
                        <th class="w-1/4 px-6 py-3 text-right">Price</th>
                        <th class="w-1/4 px-6 py-3 text-right">Total Price</th>
                        <th class="w-1/4 px-1 py-3 text-right">Available Sets</th> <!-- New Column Header -->
                    </tr>
                </thead>
                <tbody>

                    <tr class="bg-green-200 font-bold">
                        <td class="px-2 py-4 border-b border-gray-300">Before {{ \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth)->format('F') }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($soFarTotalStockOut, 2) }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($soFarAverageStockOutPrice, 2) }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($soFarTotalStockOutPrice, 2) }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right"colspan="3">{{ number_format($soFarStockIn->sum('sets') - $soFarStockOut->sum('sets'), 2) }}</td> <!-- New Column Data -->
                    </tr>


                    <!-- Stock Out Data Rows -->
                    @php
                        $cumulativeStockIn = $soFarStockIn->sum('sets'); // Starting from the stock in before this month
                        $cumulativeStockOut = $soFarStockOut->sum('sets'); // Starting from the stock out before this month
                    @endphp
                    @foreach ($completeMonth as $day)
                        @php
                            $formattedDate = $day->format('Y-m-d');
                            $dayStockInData = $stockInData->firstWhere('date', $formattedDate) ?? ['sets' => 0];
                            $dayStockOutData = $stockOutData->get($formattedDate, ['sets' => 0, 'price' => 0, 'total_price' => 0]);

                            // Update cumulative totals
                            $cumulativeStockIn += $dayStockInData['sets'];
                            $cumulativeStockOut += $dayStockOutData['sets'];

                            // Calculate available sets
                            $availableSets = $cumulativeStockIn - $cumulativeStockOut;

                            $isDataAvailable = $dayStockOutData['sets'] > 0 || $dayStockOutData['price'] > 0 || $dayStockOutData['total_price'] > 0;
                        @endphp
                        <tr class="{{ $isDataAvailable ? 'bg-gray-200' : '' }}">
                            <td class="px-6 py-4 border-b border-gray-300">{{ $day->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($dayStockOutData['sets'], 2) }}</td>
                            <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($dayStockOutData['price'], 2) }}</td>
                            <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($dayStockOutData['total_price'], 2) }}</td>
                            <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($availableSets, 2) }}</td> <!-- New Column Data -->
                        </tr>
                    @endforeach

                    <!-- Stock Out This Month Total -->
                    <tr class="bg-blue-200 font-bold">
                        <td class="py-4 border-b border-gray-300" colspan="1">This Month Total:</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($totalStockOut, 2) }}</td> <!-- Total Sets Sold This Month -->
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($averageStockOutPrice, 2) }}</td> <!-- Average Price per Set Sold This Month -->
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($totalStockOutPrice, 2) }}</td> <!-- Total Price of Sets Sold This Month -->
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($availableSets, 2) }}</td> <!-- Available Sets -->
                    </tr>


                    <!-- Cumulative Stock Out Total (So Far + This Month) -->
                    <tr class="bg-yellow-200 font-bold">
                        <td class="py-4 border-b border-gray-300" colspan="1">Total:</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($soFarTotalStockOut + $totalStockOut, 2) }}</td> <!-- Total Sets Sold -->
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($averageStockOutPrice, 2) }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($cumulativeStockOutPrice, 2) }}</td> <!-- Total Price -->
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ number_format($availableSets, 2) }}</td>
                    </tr>

                </tbody>
            </table>
            <div class="mt-6 text-right">
                <button wire:click="downloadPdf" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Download PDF</button>
            </div>
        </div>


    </div>
</div>
