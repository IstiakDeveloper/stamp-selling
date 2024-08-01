<?php

namespace App\Livewire\Report;

use App\Models\RejectOrFree;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class RejectOrFreeReportComponent extends Component
{
    public $year;
    public $month;
    public $rejectOrFreeData;
    public $totalSets;
    public $totalPurchasePrice;
    public $monthNameYear;


    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
    }

    public function updated($propertyName)
    {
        if ($this->year && $this->month) {
            $this->generateReport();
        }
    }

    public function generateReport()
    {
        // Get reject or free data for the selected month
        $this->rejectOrFreeData = RejectOrFree::whereYear('date', $this->year)
                            ->whereMonth('date', $this->month)
                            ->get();

        // Calculate totals
        $this->totalSets = $this->rejectOrFreeData->sum('sets');
        $this->totalPurchasePrice = $this->rejectOrFreeData->sum('purchase_price_total');

        // Format month and year
        $this->monthNameYear = Carbon::createFromDate($this->year, $this->month, 1)->format('F Y');
    }

    public function downloadPdf()
    {
        $data = [
            'rejectOrFreeData' => $this->rejectOrFreeData,
            'totalSets' => $this->totalSets,
            'totalPurchasePrice' => $this->totalPurchasePrice,
            'monthYear' => $this->monthNameYear
        ];

        $pdf = Pdf::loadView('pdf.reject-or-free-report', $data);

        return response()->streamDownload(
            fn() => print($pdf->output()), 
            'reject-or-free-report-' . $this->monthNameYear . '.pdf'
        );
    }

    public function render()
    {
        return view('livewire.report.reject-or-free-report-component');
    }
}
