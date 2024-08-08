<div class="max-w-6xl mx-auto px-4">
    <div class="flex justify-around mb-4 py-4 border rounded-lg">
        <a href="{{route('branch_sale')}}" class="flex items-center p-4 bg-white shadow-md rounded-lg text-gray-700 hover:bg-blue-500 hover:text-white transition duration-300 ease-in-out active:bg-blue-700">
            <x-lucide-shopping-cart class="h-6 w-6 mr-2" />
            <span>Sell (Branch)</span>
        </a>
        <a href="{{route('office_sale')}}" class="flex items-center p-4 bg-white shadow-md rounded-lg text-gray-700 hover:bg-blue-500 hover:text-white transition duration-300 ease-in-out active:bg-blue-700">
            <x-lucide-store class="h-6 w-6 mr-2" />
            <span>Sell (HO)</span>
        </a>
        <a href="{{route('expences')}}" class="flex items-center p-4 bg-white shadow-md rounded-lg text-gray-700 hover:bg-blue-500 hover:text-white transition duration-300 ease-in-out active:bg-blue-700">
            <x-lucide-wallet class="h-6 w-6 mr-2" />
            <span>Expense</span>
        </a>
    </div>

    <!-- Filter Section -->
    <div class="mb-2">
        <form class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 p-4 bg-white-d shadow rounded-lg">
            <div class="flex flex-col">
                <select wire:model.live="selectedYear" id="year" class="p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <select wire:model.live="selectedMonth" id="month" class="p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($months as $month)
                        <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                    @endforeach
                </select>
            </div>
        </form>        
    </div>

    <!-- Metrics Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <div class="bg-white-d shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Total Balance</span>
                <h2 class="text-2xl font-bold">৳{{ number_format($netTotalBalance, 2) }}</h2>
            </div>
            <x-lucide-database class="text-blue-500 h-10 w-10" />
        </div>

        <div class="bg-white-d shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Bank Balance</span>
                <h2 class="text-2xl font-bold">৳{{ number_format($totalBalance, 2) }}</h2>
            </div>
            <x-lucide-currency class="text-blue-500 h-10 w-10" />
        </div>

        <!-- Total Sale Amount -->
        <div class="bg-white-d shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Total Sale Amount</span>
                <h2 class="text-2xl font-bold">৳{{ number_format($totalSaleAmount, 2) }}</h2>
            </div>
            <x-lucide-shopping-bag class="text-blue-500 h-10 w-10" />
        </div>

        <!-- Total Payment Amount -->
        <div class="bg-white-d shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Total Cash Receive</span>
                <h2 class="text-2xl font-bold">৳{{ number_format($totalPaymentAmount, 2) }}</h2>
            </div>
            <x-lucide-circle-dollar-sign class="text-blue-500 h-10 w-10" />
        </div>

        <!-- Total Outstanding Balance -->
        <div class="bg-white-d shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Total Due</span>
                <h2 class="text-2xl font-bold">৳{{ number_format($totalOutstandingBalance, 2) }}</h2>
            </div>
            <x-lucide-hand-coins class="text-blue-500 h-10 w-10" />
        </div>


        <!-- Average Stamp Price per Set -->
        <div class="bg-white-d shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Buy Price</span>
                <h2 class="text-2xl font-bold">৳{{ number_format($totalSetsBuyPrice, 2) }}</h2>
            </div>
            <x-lucide-credit-card class="text-blue-500 h-10 w-10" />
        </div>

                <!-- Average Stamp Price per Set -->
                <div class="bg-white-d shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
                    <div>
                        <span class="text-gray-400 text-sm">Per Set Buy Price</span>
                        <h2 class="text-2xl font-bold">৳{{ number_format($averageStampPricePerSet, 2) }}</h2>
                    </div>
                    <x-lucide-diamond-percent class="text-blue-500 h-10 w-10" />
                </div>


        <div class="bg-white-d shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Expences & Reject</span>
                <h2 class="text-2xl font-bold">৳{{ number_format($netExpences, 2) }}</h2>
            </div>
            <x-lucide-wallet class="text-blue-500 h-10 w-10" />
        </div>

        <div class="bg-white-d shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Net Profit</span>
                <h2 class="text-2xl font-bold">৳{{ number_format($totalProfit, 2) }}</h2>
            </div>
            <x-lucide-banknote class="text-blue-500 h-10 w-10" />
        </div>

        <!-- Reject Total -->
        <div class="bg-white-d shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Total Rejects</span>
                <h2 class="text-2xl font-bold">{{ $rejectTotal }}</h2>
            </div>
            <x-lucide-ban class="text-blue-500 h-10 w-10" />
        </div>

        {{-- <!-- Total Sets Bought -->
        <div class="bg-white-d shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Total Sets Bought</span>
                <h2 class="text-2xl font-bold">{{ $totalSetsBuy }}</h2>
            </div>
            <x-lucide-shopping-cart class="text-blue-500 h-10 w-10" />
        </div> --}}
    
        <!-- Total Stamp Available -->
        <div class="bg-white-d shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Total Stamp Available</span>
                <h2 class="text-2xl font-bold">{{ $totalStampAvailable }}</h2>
            </div>
            <x-lucide-box class="text-blue-500 h-10 w-10" />
        </div>

        <!-- Total Branches -->
        <div class="bg-white-d shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Total Branches</span>
                <h2 class="text-2xl font-bold">{{ $totalBranch }}</h2>
            </div>
            <x-lucide-store class="text-blue-500 h-10 w-10" />
        </div>
    </div>
</div>
