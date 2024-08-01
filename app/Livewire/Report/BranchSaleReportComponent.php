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
    public $year;
    public $month;
    public $branchId;
    public $outstandingBalance;
    public $sales;
    public $monthNameYear;
    public $totalSets;
    public $totalPrice;
    public $totalCash;
    public $totalDue;

    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
    }

    public function updated($propertyName)
    {
        if ($this->branchId && $this->year && $this->month) {
            $this->generateReport();
        }
    }

    public function generateReport()
    {
        // Calculate the previous month
        $previousMonth = Carbon::create($this->year, $this->month, 1)->subMonth();

        // Get outstanding balance for the previous month from the history table
        $previousBalance = OutstandingBalanceHistory::where('branch_id', $this->branchId)
            ->whereYear('date', $previousMonth->year)
            ->whereMonth('date', $previousMonth->month)
            ->orderBy('date', 'desc')
            ->value('outstanding_balance');

        $this->outstandingBalance = $previousBalance ?? 0;

        // Get sales data for the selected month
        $this->sales = BranchSale::where('branch_id', $this->branchId)
                        ->whereYear('date', $this->year)
                        ->whereMonth('date', $this->month)
                        ->get();

        // Calculate totals
        $this->totalSets = $this->sales->sum('sets');
        $this->totalPrice = $this->sales->sum('total_price');
        $this->totalCash = $this->sales->sum('cash');
        $this->totalDue = $this->totalPrice - $this->totalCash;

        // Format month and year
        $this->monthNameYear = Carbon::createFromDate($this->year, $this->month, 1)->format('F Y');
    }

    public function downloadPdf()
    {
        $branch = Branch::find($this->branchId);
        $monthName = Carbon::createFromDate($this->year, $this->month, 1)->format('F');

        $data = [
            'outstandingBalance' => $this->outstandingBalance,
            'sales' => $this->sales,
            'branchName' => $branch ? $branch->branch_name : 'Unknown Branch',
            'monthYear' => $monthName . ' ' . $this->year,
            'totalSets' => $this->totalSets,
            'totalPrice' => $this->totalPrice,
            'totalCash' => $this->totalCash,
            'totalDue' => $this->totalDue,
        ];

        $pdf = Pdf::loadView('pdf.branch-sale-report', $data);

        return response()->streamDownload(
            fn() => print($pdf->output()), 
            'branch-sale-report-' . $monthName . '-' . $this->year . '.pdf'
        );
    }

    public function render()
    {
        $branches = Branch::all();
        return view('livewire.report.branch-sale-report-component', ['branches' => $branches]);
    }

}
