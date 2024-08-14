<?php

namespace App\Livewire\Report;

use App\Models\Branch;
use App\Models\BranchSale;
use App\Models\HeadOfficeSale;
use App\Models\OutstandingBalanceHistory;
use App\Models\RejectOrFree;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class AllBranchReportComponent extends Component
{
    public $fromDate;
    public $toDate;
    public $reportData = [];
    public $totalSets = 0;
    public $totalPrice = 0;
    public $totalCash = 0;
    public $totalDue = 0;
    public $hoSetsSum = 0;
    public $hoTotalPriceSum = 0;
    public $hoCashSum = 0;
    public $rfSetsSum = 0;


    public function mount()
    {
        // Set default date range to the current month
        $this->fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->toDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->generateReport();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['fromDate', 'toDate'])) {
            $this->generateReport();
        }
    }

    public function generateReport()
{
    // Initialize the reportData array and cumulative totals
    $this->reportData = [];
    $this->totalSets = 0;
    $this->totalPrice = 0;
    $this->totalCash = 0;
    $this->totalDue = 0;

    // Fetch all branches
    $branches = Branch::all();

    foreach ($branches as $branch) {
        // Start with the query for sales data
        $query = BranchSale::where('branch_id', $branch->id);

        // Apply date filters if they are provided
        if ($this->fromDate && $this->toDate) {
            $fromDate = Carbon::parse($this->fromDate)->format('Y-m-d');
            $toDate = Carbon::parse($this->toDate)->format('Y-m-d');
            $query->whereBetween('date', [$fromDate, $toDate]);
        } elseif ($this->fromDate) {
            $fromDate = Carbon::parse($this->fromDate)->format('Y-m-d');
            $query->where('date', '>=', $fromDate);
        } elseif ($this->toDate) {
            $toDate = Carbon::parse($this->toDate)->format('Y-m-d');
            $query->where('date', '<=', $toDate);
        }

        // Fetch sales data
        $sales = $query->get();

        // Calculate total sets, price, and cash received within the date range
        $sets = $sales->sum('sets');
        $price = $sales->sum('total_price');
        $cash = $sales->sum('cash');

        // Fetch the initial outstanding balance of the branch when it was created
        $initialOutstanding = $branch->outstanding_balance;

        // Fetch the outstanding balance before the 'fromDate'
        $previousOutstanding = OutstandingBalanceHistory::where('branch_id', $branch->id)
            ->whereDate('date', '<', $this->fromDate)
            ->orderBy('date', 'desc')
            ->value('outstanding_balance') ?? 0;

        // Calculate the total due: initial outstanding + previous outstanding + price - cash
        $totalDue = $initialOutstanding + $previousOutstanding + $price - $cash;

        $previousOutstanding = $initialOutstanding + $previousOutstanding;

        $this->hoSetsSum = HeadOfficeSale::sum('sets');
        $this->hoTotalPriceSum = HeadOfficeSale::sum('total_price');
        $this->hoCashSum = HeadOfficeSale::sum('cash');
        $this->rfSetsSum = RejectOrFree::sum('sets');
        // Add the branch data to the reportData array
        $this->reportData[] = [
            'branch_name' => $branch->branch_name,
            'serial_number' => $branch->id,
            'sets' => $sets,
            'price' => $price,
            'cash' => $cash,
            'previous_outstanding' => $previousOutstanding,
            'total_due' => $totalDue
        ];

        // Accumulate the totals
        $this->totalSets += $sets;
        $this->totalPrice += $price;
        $this->totalCash += $cash;
        $this->totalDue += $totalDue;
    }
}


    public function downloadPdf()
    {
        $this->hoSetsSum = HeadOfficeSale::sum('sets');
        $this->hoTotalPriceSum = HeadOfficeSale::sum('total_price');
        $this->hoCashSum = HeadOfficeSale::sum('cash');
        $this->rfSetsSum = RejectOrFree::sum('sets');
        
        $data = [
            'reportData' => $this->reportData,
            'totalSets' => $this->totalSets,
            'totalPrice' => $this->totalPrice,
            'totalCash' => $this->totalCash,
            'totalDue' => $this->totalDue,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
            'hoSetsSum' => $this->hoSetsSum,
            'hoTotalPriceSum' => $this->hoTotalPriceSum,
            'hoCashSum' => $this->hoCashSum,
            'rfSetsSum' => $this->rfSetsSum,

        ];

        $pdf = Pdf::loadView('pdf.all-branch-report', $data);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'all-branch-report-' . ($this->fromDate ?? 'no-date') . '-to-' . ($this->toDate ?? 'no-date') . '.pdf'
        );
    }

    public function render()
    {
        return view('livewire.report.all-branch-report-component');
    }
}
