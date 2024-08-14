<?php

namespace App\Livewire;

use App\Models\Balance;
use App\Models\Branch;
use App\Models\BranchSale;
use App\Models\BranchSaleOutstanding;
use App\Models\Expense;
use App\Models\FundManagement;
use App\Models\HeadOfficeSale;
use App\Models\Money;
use App\Models\RejectOrFree;
use App\Models\SofarNetProfit;
use App\Models\Stock;
use App\Models\TotalFund;
use App\Models\TotalStock;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class BalanceSheetAll extends Component
{
    public $totalCashIn;
    public $cashWas;
    public $soFarCash;
    public $totalBankOrHandBalance;
    public $stockStampBuyPrice;
    public $totalOutstandingBalance;
    public $outstandingTotal;
    public $netProfit;

    public function mount()
    {
        $this->generateReport();
    }

    private function generateReport()
    {
        // Calculate total cash in and cash out
        $totalCashIn = TotalFund::get()->sum('total_fund');
        $totalCashOut = Money::where('type', 'cash_out')
            ->sum('amount');
        $this->totalCashIn = $totalCashIn;

        // Calculate after-sale outstanding
        $afterSaleOutstanding = BranchSaleOutstanding::sum('outstanding_balance') - BranchSaleOutstanding::sum('extra_money');

        // Total outstanding balance and cash was
        $this->totalOutstandingBalance = Branch::sum('outstanding_balance');
        $this->cashWas = $this->totalOutstandingBalance;

        // Calculate outstanding total and cash so far
        $this->outstandingTotal = $afterSaleOutstanding + $this->totalOutstandingBalance;
        $this->soFarCash = $this->totalCashIn + $this->cashWas;

        // Total bank or hand balance
        $this->totalBankOrHandBalance = Balance::sum('total_balance');

        // Total sets bought and average stamp price per set
        $totalSetsBuy = Stock::sum('sets');
        $totalSetsBuyPrice = Stock::sum('total_price');
        $averageStampPricePerSet = $totalSetsBuy > 0 ? $totalSetsBuyPrice / $totalSetsBuy : 0;
        // Calculate stock stamp buy price
        $totalStampAvailable = TotalStock::sum('total_sets');
        $this->stockStampBuyPrice = $totalStampAvailable * $averageStampPricePerSet;

        // Calculate total reject or free
        $totalRejectOrFreeSets = RejectOrFree::sum('sets');
        $totalRejectOrFree = $totalRejectOrFreeSets * $averageStampPricePerSet;

        // Calculate total set sale price
        $totalBranchSaleSets = BranchSale::sum('sets');
        $totalHeadOfficeSaleSets = HeadOfficeSale::sum('sets');
        $totalBranchSalePrice = BranchSale::sum('total_price');
        $totalHeadOfficeSalePrice = HeadOfficeSale::sum('total_price');
        $saleSetBuyPrice = ($totalBranchSaleSets + $totalHeadOfficeSaleSets) * $averageStampPricePerSet;

        // Calculate total lose
        $totalExpense = Expense::sum('amount');
        $totalLose = $totalRejectOrFree + $saleSetBuyPrice + $totalExpense;


        // Calculate total sale
        $totalSale = $totalBranchSalePrice + $totalHeadOfficeSalePrice;

        // Fetch soFarNetProfit amount
        $soFarNetProfitAmount = SofarNetProfit::sum('amount');

        // Calculate net profit
        $netProfit = $totalSale - $totalLose + $soFarNetProfitAmount;

        // Set net profit to class property if needed
        $this->netProfit = $netProfit;
    }

    public function downloadPdf()
    {
        $data = [
            'totalCashIn' => $this->totalCashIn,
            'cashWas' => $this->cashWas,
            'soFarCash' => $this->soFarCash,
            'totalBankOrHandBalance' => $this->totalBankOrHandBalance,
            'stockStampBuyPrice' => $this->stockStampBuyPrice,
            'outstandingTotal' => $this->outstandingTotal,
            'netProfit' => $this->netProfit,
            'month' => 'All Time',
            'year' => '',
        ];

        $pdf = Pdf::loadView('pdf.balance-sheet-all', $data);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'balance-sheet-all.pdf'
        );
    }

    public function render()
    {
        return view('livewire.balance-sheet-all');
    }
}
