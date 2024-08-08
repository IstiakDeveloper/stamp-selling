<?php

namespace App\Livewire\Report;

use App\Models\HeadOfficeSale;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class HeadOfficeSaleReportComponent extends Component
{
    public $year;
    public $month;
    public $sales;
    public $monthNameYear;
    public $totalSets;
    public $totalPrice;
    public $totalCash;

    public function mount()
    {
        $this->year = now()->year;
        $this->month = 'all';
    }

    public function updated($propertyName)
    {
        if ($this->year) {
            $this->generateReport();
        }
    }

    public function generateReport()
    {
        if ($this->month === 'all') {
            // Get sales data for the selected year
            $this->sales = HeadOfficeSale::whereYear('date', $this->year)->get();
            $this->monthNameYear = $this->year;  // Just the year for all months
        } else {
            // Get sales data for the selected month
            $this->sales = HeadOfficeSale::whereYear('date', $this->year)
                            ->whereMonth('date', $this->month)
                            ->get();
            $this->monthNameYear = Carbon::createFromDate($this->year, $this->month, 1)->format('F Y');
        }

        // Calculate totals
        $this->totalSets = $this->sales->sum('sets');
        $this->totalPrice = $this->sales->sum('total_price');
        $this->totalCash = $this->sales->sum('cash');
    }

    public function downloadPdf()
    {
        $data = [
            'sales' => $this->sales,
            'monthYear' => $this->monthNameYear,
            'totalSets' => $this->totalSets,
            'totalPrice' => $this->totalPrice,
            'totalCash' => $this->totalCash
        ];

        $pdf = Pdf::loadView('pdf.head-office-sale-report', $data);

        return response()->streamDownload(
            fn() => print($pdf->output()), 
            'head-office-sale-report.pdf'
        );
    }

    public function render()
    {
        return view('livewire.report.head-office-sale-report-component', ['monthNameYear' => $this->monthNameYear]);
    }
    
}
