<?php

namespace App\Livewire;

use App\Models\Balance;
use App\Models\Branch;
use App\Models\BranchSale;
use App\Models\BranchSaleOutstanding;
use App\Models\Expense;
use App\Models\HeadOfficeSale;
use App\Models\Money;
use App\Models\Payment;
use App\Models\RejectOrFree;
use App\Models\Stock;
use App\Models\TotalStock;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalBalance;
    public $totalBranch;
    public $totalOutstandingBalance;
    public $totalPaymentAmount;
    public $totalBranchSaleAmount;
    public $totalOfficeSaleAmount;
    public $totalSaleAmount;
    public $rejectTotal;
    public $totalCashIn;
    public $totalCashOut;
    public $totalInvesmentAmount;
    public $averageStampPricePerSet;
    public $totalStampAvailable;
    public $totalStampSale;
    public $totalProfit;
    public $totalStampSaleBuyPrice;
    public $netProfit;
    public $totalSetsBuy;
    public $totalSetsBuyPrice;
    public $totalExpences;
    public $totalRejectFreePrice;
    public $netExpences;
    public $netTotalBalance;

    public $selectedYear;
    public $selectedMonth;

    public function mount()
    {
        $this->selectedYear = null;
        $this->selectedMonth = null;
        $this->calculateMetrics();
    }

    public function updatedSelectedYear()
    {
        $this->calculateMetrics();
    }

    public function updatedSelectedMonth()
    {
        $this->calculateMetrics();
    }

    private function calculateMetrics()
    {
        $this->totalBalance = Balance::first()->total_balance ?? 0;
        $this->totalBranch = Branch::count();
        $this->totalOutstandingBalance = Branch::sum('outstanding_balance');

        $paymentQuery = Payment::query();
        $branchSaleQuery = BranchSale::query();
        $headOfficeSaleQuery = HeadOfficeSale::query();
        $rejectOrFreeQuery = RejectOrFree::query();
        $stockQuery = Stock::query();
        $expenseQuery = Expense::query();

        if ($this->selectedYear) {
            $paymentQuery->whereYear('date', $this->selectedYear);
            $branchSaleQuery->whereYear('date', $this->selectedYear);
            $headOfficeSaleQuery->whereYear('date', $this->selectedYear);
            $rejectOrFreeQuery->whereYear('date', $this->selectedYear);
            $stockQuery->whereYear('date', $this->selectedYear);
            $expenseQuery->whereYear('date', $this->selectedYear);

            if ($this->selectedMonth) {
                $paymentQuery->whereMonth('date', $this->selectedMonth);
                $branchSaleQuery->whereMonth('date', $this->selectedMonth);
                $headOfficeSaleQuery->whereMonth('date', $this->selectedMonth);
                $rejectOrFreeQuery->whereMonth('date', $this->selectedMonth);
                $stockQuery->whereMonth('date', $this->selectedMonth);
                $expenseQuery->whereMonth('date', $this->selectedMonth);
            }
        }

        $this->totalPaymentAmount = $paymentQuery->sum('amount');
        $this->totalBranchSaleAmount = $branchSaleQuery->sum('total_price');
        $totalBranchSaleSets = $branchSaleQuery->sum('sets');
        $this->totalOfficeSaleAmount = $headOfficeSaleQuery->sum('cash');
        $totalOfficeSaleSets = $headOfficeSaleQuery->sum('sets');
        $totalSaleSets = $totalBranchSaleSets + $totalOfficeSaleSets;
        
        $this->totalSaleAmount = $this->totalBranchSaleAmount + $this->totalOfficeSaleAmount;
        $this->rejectTotal = $rejectOrFreeQuery->sum('sets');

        $this->totalCashIn = Money::getTotalCashIn($this->selectedYear, $this->selectedMonth);
        $this->totalCashOut = Money::getTotalCashOut($this->selectedYear, $this->selectedMonth);
        $this->totalInvesmentAmount = $this->totalCashIn - $this->totalCashOut;
        $this->totalSetsBuy = $stockQuery->sum('sets');
        $this->totalSetsBuyPrice = $stockQuery->sum('total_price');

        if ($this->totalSetsBuy > 0) {
            $this->averageStampPricePerSet = $this->totalSetsBuyPrice / $this->totalSetsBuy;
        } else {
            $this->averageStampPricePerSet = 0;
        }
        
        $saleStampBuyAmount = $this->averageStampPricePerSet * $totalSaleSets;

        $this->totalStampAvailable = TotalStock::sum('total_sets');

        $this->totalExpences = $expenseQuery->sum('amount');
        $totalRejectFreeSets = $rejectOrFreeQuery->sum('sets');

        $this->totalRejectFreePrice = $totalRejectFreeSets * $this->averageStampPricePerSet;
        $this->netExpences = $this->totalExpences + $this->totalRejectFreePrice;


        $this->totalProfit = $this->totalSaleAmount - $this->netExpences - $saleStampBuyAmount;

        // Calculate outstanding balance after sales
        $branchOutstandingAfterSales = BranchSaleOutstanding::sum('outstanding_balance') - BranchSaleOutstanding::sum('extra_money');

        // Total outstanding balance from Branch model
        $totalBranchOutstandingBalance = Branch::sum('outstanding_balance');

        // Calculate total outstanding balance
        $this->totalOutstandingBalance = $branchOutstandingAfterSales + $totalBranchOutstandingBalance;

        // Calculate net total balance
        $this->netTotalBalance = $this->totalBalance + $this->totalOutstandingBalance;
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'years' => range(2020, now()->year),
            'months' => range(1, 12),
        ]);
    }
}
