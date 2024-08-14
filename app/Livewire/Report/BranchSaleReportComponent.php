<?php

namespace App\Livewire\Report;

use App\Models\Branch;
use App\Models\BranchSale;
use App\Models\OutstandingBalanceHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class BranchSaleReportComponent extends Component
{
    public $fromDate;
    public $toDate;
    public $branchId;
    public $soFarOutstanding = 0;
    public $sales;
    public $totalSets = 0;
    public $totalPrice = 0;
    public $totalCash = 0;
    public $totalDue = 0;
    public $serialNumber = 1;

    public function mount()
    {
        $this->fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->toDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->generateReport();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['branchId', 'fromDate', 'toDate'])) {
            $this->generateReport();
        }
    }



    public function generateReport()
    {
        // Calculate the outstanding balance before the report period
        $previousBalanceDate = $this->fromDate ? Carbon::parse($this->fromDate)->subDay() : Carbon::now()->subDay();
    
        // Fetch the last outstanding balance before the report period
        $previousBalance = OutstandingBalanceHistory::where('branch_id', $this->branchId)
            ->where('date', '<=', $previousBalanceDate)
            ->orderBy('date', 'desc')
            ->value('outstanding_balance');
    
        // Fallback to the branch's initial outstanding balance if no history found
        if (is_null($previousBalance)) {
            $branch = Branch::find($this->branchId);
            $previousBalance = $branch ? $branch->outstanding_balance : 0;
        }
    
        $this->soFarOutstanding = $previousBalance ?? 0;
    
        // Query sales data within the specified date range
        $query = BranchSale::where('branch_id', $this->branchId);
    
        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('date', [$this->fromDate, $this->toDate]);
        } elseif ($this->fromDate) {
            $query->where('date', '>=', $this->fromDate);
        } elseif ($this->toDate) {
            $query->where('date', '<=', $this->toDate);
        }
    
        $this->sales = $query->get();
    
        // Initialize cumulative due with the outstanding balance up to the start of the period
        $cumulativeDue = $this->soFarOutstanding;
    
        // Calculate totals for display
        $this->totalSets = $this->sales->sum('sets');
        $this->totalPrice = $this->sales->sum('total_price');
        $this->totalCash = $this->sales->sum('cash');
    
        foreach ($this->sales as $sale) {
            $previousDue = $cumulativeDue;
            
            // Update the cumulative due by adding the sale's price and subtracting the cash received
            $cumulativeDue += $sale->total_price - $sale->cash;
            
            // Assign the cumulative due to the sale's total_due attribute
            $sale->total_due = $cumulativeDue;
            
            // Store the previous due in a new attribute
            $sale->previous_due = $previousDue;
        }
    
        // Calculate the final total due for the entire period
        $this->totalDue = $cumulativeDue;
    }
    

    public function downloadPdf()
    {
        $branch = Branch::find($this->branchId);

        $data = [
            'soFarOutstanding' => $this->soFarOutstanding,
            'sales' => $this->sales,
            'branchName' => $branch ? $branch->branch_name : 'Unknown Branch',
            'totalSets' => $this->totalSets,
            'totalPrice' => $this->totalPrice,
            'totalCash' => $this->totalCash,
            'totalDue' => $this->totalDue,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
        ];

        $pdf = Pdf::loadView('pdf.branch-sale-report', $data);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'branch-sale-report-' . ($this->fromDate ?? 'no-date') . '-to-' . ($this->toDate ?? 'no-date') . '.pdf'
        );
    }

    public function calculateTotals()
    {
        $this->totalSets = $this->sales->sum('sets');
        $this->totalPrice = $this->sales->sum('total_price');
        $this->totalCash = $this->sales->sum('cash');
        $this->totalDue = max($this->totalPrice - $this->totalCash, 0);
    }

    public function render()
    {
        $this->calculateTotals();
        $branches = Branch::all();
        return view('livewire.report.branch-sale-report-component', ['branches' => $branches]);
    }

}
