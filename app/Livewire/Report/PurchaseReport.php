<?php

namespace App\Livewire\Report;

use App\Models\Stock;
use App\Models\TotalStock;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class PurchaseReport extends Component
{
    public $selectedYear;
    public $selectedMonth;
    public $stocks = [];
    public $openingStock;
    public $years = [];
    public $months = [
        '01' => 'January',
        '02' => 'February',
        '03' => 'March',
        '04' => 'April',
        '05' => 'May',
        '06' => 'June',
        '07' => 'July',
        '08' => 'August',
        '09' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    ];

    public function mount()
    {
        $this->selectedYear = Carbon::now()->format('Y');
        $this->selectedMonth = 'all';
        $this->years = range(Carbon::now()->format('Y'), 2000); // Adjust range as needed
        $this->filterStocks();
    }

    public function filterStocks()
    {
        $query = Stock::query();

        if ($this->selectedYear !== 'all') {
            $query->whereYear('date', $this->selectedYear);
        }

        if ($this->selectedMonth !== 'all') {
            $query->whereMonth('date', $this->selectedMonth);
        }

        $this->stocks = $query->get();
        $this->calculateOpeningStock();
    }

    public function calculateOpeningStock()
    {
        if ($this->selectedYear === 'all' && $this->selectedMonth === 'all') {
            $this->openingStock = 0;
            return;
        }

        $endOfPreviousMonth = Carbon::parse($this->selectedYear . '-' . ($this->selectedMonth !== 'all' ? $this->selectedMonth : '01') . '-01')
            ->subMonth()
            ->endOfMonth()
            ->format('Y-m-d');

        // Get total pieces from TotalStock up to the end of the previous month
        $totalPiecesBeforePeriod = TotalStock::sum('total_pieces');

        // Calculate total pieces up to the end of the previous month
        $totalPiecesUpToEndOfMonth = Stock::whereDate('date', '<=', $endOfPreviousMonth)
            ->sum('pieces');

        $this->openingStock = $totalPiecesBeforePeriod - $totalPiecesUpToEndOfMonth;
    }

    public function handleFilterChange()
    {
        $this->filterStocks();
    }

    public function downloadPdf()
    {
        $data = [
            'stocks' => $this->stocks,
            'openingStock' => $this->openingStock,
        ];

        $pdf = Pdf::loadView('pdf.purchase-report', $data);

        return response()->streamDownload(
            fn() => print($pdf->output()), 
            'purchase-report.pdf'
        );
    }

    public function render()
    {
        return view('livewire.report.purchase-report', [
            'stocks' => $this->stocks,
            'openingStock' => $this->openingStock,
            'years' => $this->years,
            'months' => $this->months,
        ]);
    }
}
