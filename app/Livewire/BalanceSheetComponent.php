<?php

namespace App\Livewire;

use App\Models\RejectOrFree;
use App\Models\Expense;
use App\Models\BranchSale;
use App\Models\HeadOfficeSale;
use App\Models\Stock;
use App\Models\TotalStock;
use Carbon\Carbon;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BalanceSheetComponent extends Component
{
    public $month = 'all';
    public $year = 'all';
    public $startDate;
    public $endDate;

    public function mount() {
        $this->setDateRange();
    }

    public function updatedMonth() {
        $this->setDateRange();
    }

    public function updatedYear() {
        $this->setDateRange();
    }

    private function setDateRange() {
        if ($this->year === 'all' && $this->month === 'all') {
            $this->startDate = Carbon::parse('0000-01-01');
            $this->endDate = Carbon::parse('9999-12-31');
        } elseif ($this->year === 'all') {
            $this->startDate = Carbon::create(null, $this->month, 1)->startOfMonth();
            $this->endDate = Carbon::create(null, $this->month, 1)->endOfMonth();
        } elseif ($this->month === 'all') {
            $this->startDate = Carbon::create($this->year, 1, 1)->startOfYear();
            $this->endDate = Carbon::create($this->year, 12, 31)->endOfYear();
        } else {
            $this->startDate = Carbon::create($this->year, $this->month, 1)->startOfMonth();
            $this->endDate = Carbon::create($this->year, $this->month, 1)->endOfMonth();
        }
    }

    public function exportCSV()
    {
        $fileName = 'balance_sheet_' . $this->year . '_' . $this->month . '.csv';
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
    
        $callback = function() {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Type', 'Sum']);
    
            $rejectOrFreeSum = RejectOrFree::whereBetween('date', [$this->startDate, $this->endDate])->sum('purchase_price_total');
            $expenseSum = Expense::whereBetween('date', [$this->startDate, $this->endDate])->sum('amount');
            $branchSaleSetsSum = BranchSale::whereBetween('date', [$this->startDate, $this->endDate])->sum('sets');
            $branchSalePriceSum = BranchSale::whereBetween('date', [$this->startDate, $this->endDate])->sum('total_price');
            $headOfficeSaleSetsSum = HeadOfficeSale::whereBetween('date', [$this->startDate, $this->endDate])->sum('sets');
            $headOfficeSalePriceSum = HeadOfficeSale::whereBetween('date', [$this->startDate, $this->endDate])->sum('total_price');

            $stockTotalPriceSum = Stock::sum('total_price');
            $totalStockSetsSum = Stock::sum('sets');
            $totalStockSetsAvailble = TotalStock::sum('total_sets');
            $purchasePriceSet = $stockTotalPriceSum / $totalStockSetsSum;
            
            $availableStampTotalPrice = $totalStockSetsAvailble * $purchasePriceSet;
    
            $totalLoss = $rejectOrFreeSum + $expenseSum;
            $totalRevenue = $branchSalePriceSum + $headOfficeSalePriceSum;
            $stampSalesSets = $branchSaleSetsSum + $headOfficeSaleSetsSum;
            $saleStampBuyPrice = $stampSalesSets * $purchasePriceSet;
            $netProfit = $totalRevenue - $totalLoss - $saleStampBuyPrice;
    
            fputcsv($handle, ['Reject or Free Sum', $rejectOrFreeSum]);
            fputcsv($handle, ['Expense Sum', $expenseSum]);
            fputcsv($handle, ['Branch Sale Sets Sum', $branchSaleSetsSum]);
            fputcsv($handle, ['Branch Sale Price Sum', $branchSalePriceSum]);
            fputcsv($handle, ['Head Office Sale Sets Sum', $headOfficeSaleSetsSum]);
            fputcsv($handle, ['Total Loss', $totalLoss]);
            fputcsv($handle, ['Total Revenue', $totalRevenue]);
            fputcsv($handle, ['Net Profit', $netProfit]);
    
            fclose($handle);
        };
    
        return new StreamedResponse($callback, 200, $headers);
    }
    

    
    public function render()
    {
        $rejectOrFreeSum = RejectOrFree::whereBetween('date', [$this->startDate, $this->endDate])->sum('purchase_price_total');
        $expenseSum = Expense::whereBetween('date', [$this->startDate, $this->endDate])->sum('amount');
        $branchSaleSetsSum = BranchSale::whereBetween('date', [$this->startDate, $this->endDate])->sum('sets');
        $branchSalePriceSum = BranchSale::whereBetween('date', [$this->startDate, $this->endDate])->sum('total_price');
        $headOfficeSaleSetsSum = HeadOfficeSale::whereBetween('date', [$this->startDate, $this->endDate])->sum('sets');
        $headOfficeSalePriceSum = HeadOfficeSale::whereBetween('date', [$this->startDate, $this->endDate])->sum('total_price');
        

        $stockTotalPriceSum = Stock::sum('total_price');
        $totalStockSetsSum = Stock::sum('sets');
        $totalStockSetsAvailble = TotalStock::sum('total_sets');
        $purchasePriceSet = $stockTotalPriceSum / $totalStockSetsSum;
 
        
        $availableStampTotalPrice = $totalStockSetsAvailble * $purchasePriceSet;

        $totalLoss = $rejectOrFreeSum + $expenseSum;
        $totalRevenue = $branchSalePriceSum + $headOfficeSalePriceSum;
        $stampSalesSets = $branchSaleSetsSum + $headOfficeSaleSetsSum;
        $saleStampBuyPrice = $stampSalesSets * $purchasePriceSet;
        $netProfit = $totalRevenue - $totalLoss - $saleStampBuyPrice;


        return view('livewire.balance-sheet-component', [
            'rejectOrFreeSum' => $rejectOrFreeSum,
            'expenseSum' => $expenseSum,
            'totalLoss' => $totalLoss,
            'branchSaleSetsSum' => $branchSaleSetsSum,
            'branchSalePriceSum' => $branchSalePriceSum,
            'headOfficeSaleSetsSum' => $headOfficeSaleSetsSum,
            'headOfficeSalePriceSum' => $headOfficeSalePriceSum,
            'totalRevenue' => $totalRevenue,
            'stampSalesSets' => $stampSalesSets,
            'netProfit' => $netProfit,
        ]);
    }

    



}
