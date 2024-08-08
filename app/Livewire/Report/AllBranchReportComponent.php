<?php

namespace App\Livewire\Report;

use App\Models\Branch;
use App\Models\BranchSale;
use App\Models\BranchSaleOutstanding;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class AllBranchReportComponent extends Component
{
    public $branches;
    public $branch_id;
    public $month;
    public $year;
    public $specific_date;
    public $sales;
    public $reportData;
    public $previousMonthOutstanding;

    public function mount()
    {
        $this->branches = Branch::all();
        $this->month = Carbon::now()->format('m');
        $this->year = Carbon::now()->format('Y');
        $this->specific_date = null;
        $this->generateReport();
    }

    public function render()
    {
        return view('livewire.report.all-branch-report-component', ['sales' => $this->sales]);
    }

    public function generateReport()
    {
        $query = BranchSale::query();

        if ($this->specific_date) {
            $query->whereDate('date', $this->specific_date);
        } else {
            if ($this->month && $this->year) {
                $query->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year);
            }
        }

        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }

        $this->sales = $query->get();

        $totalSets = $this->sales->sum('sets');
        $totalPrice = $this->sales->sum('total_price');
        $totalCash = $this->sales->sum('cash');

        // Calculate total outstanding balance for the selected month
        $outstandingQuery = BranchSaleOutstanding::query();

        if ($this->branch_id) {
            $outstandingQuery->where('branch_id', $this->branch_id);
        }

        if ($this->specific_date) {
            $outstandingQuery->whereDate('date', $this->specific_date);
        } else {
            if ($this->month && $this->year) {
                $outstandingQuery->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year);
            }
        }

        $totalExtraMoney = $outstandingQuery->sum('extra_money');
        $totalOutstandingBalance = $outstandingQuery->sum('outstanding_balance');

        $netOutstandingBalance = $totalOutstandingBalance - $totalExtraMoney;

        // Calculate the previous month's outstanding balance
        $currentMonthStart = Carbon::create($this->year, $this->month, 1);
        $previousMonthStart = $currentMonthStart->copy()->subMonth()->startOfMonth();
        $previousMonthEnd = $currentMonthStart->copy()->subMonth()->endOfMonth();

        // Fetch total outstanding balance excluding the current month
        $previousOutstandingQuery = BranchSaleOutstanding::whereBetween('date', [$previousMonthStart, $previousMonthEnd])
            ->get();

        $previousTotalExtraMoney = $previousOutstandingQuery->sum('extra_money');
        $previousTotalOutstandingBalance = $previousOutstandingQuery->sum('outstanding_balance');

        // Add the branch's current outstanding balance to calculate previous month total
        $branches = Branch::all();
        $branchOutstanding = $branches->sum('outstanding_balance');
        
        $this->previousMonthOutstanding = $previousTotalOutstandingBalance - $previousTotalExtraMoney + $branchOutstanding;

        $this->reportData = [
            'totalSets' => $totalSets,
            'totalPrice' => $totalPrice,
            'totalCash' => $totalCash,
            'totalOutstandingBalance' => $netOutstandingBalance,
        ];
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['month', 'year', 'branch_id', 'specific_date'])) {
            $this->generateReport();
        }
    }

    public function downloadPdf()
    {
        $this->generateReport(); // Ensure the report is up-to-date

        $monthName = Carbon::createFromDate($this->year, $this->month, 1)->format('F');

        $data = [
            'sales' => $this->sales,
            'reportData' => $this->reportData,
            'previousMonthOutstanding' => $this->previousMonthOutstanding,
            'monthYear' => $monthName . ' ' . $this->year,
        ];

        $pdf = Pdf::loadView('pdf.all-branch-report', $data);

        return response()->streamDownload(
            fn() => print($pdf->output()), 
            'all-branch-report-' . $monthName . '-' . $this->year . '.pdf'
        );
    }
}
