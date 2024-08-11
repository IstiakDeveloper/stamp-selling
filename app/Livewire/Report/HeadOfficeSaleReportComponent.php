<?php

namespace App\Livewire\Report;

use App\Models\HeadOfficeDue;
use App\Models\HeadOfficeSale;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class HeadOfficeSaleReportComponent extends Component
{
    public $selectedMonth;
    public $selectedYear;
    public $soFarDue;

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->format('m');
        $this->selectedYear = Carbon::now()->format('Y');
        $this->calculateSoFarDue();
    }

    public function render()
    {
        $sales = $this->getSalesData();
        $completeMonth = $this->getCompleteMonth();

        // Calculate the total due by adding the soFarDue to the sum of the due amounts from the sales data
        $totalDue = $this->soFarDue + $sales->sum('due');

        return view('livewire.report.head-office-sale-report-component', [
            'sales' => $sales,
            'completeMonth' => $completeMonth,
            'soFarDue' => $this->soFarDue, // Passing $soFarDue to the view
            'totalSetPrice' => $sales->sum('sets'), // Sum the total sets correctly
            'totalPrice' => $sales->sum('price'), // Sum the total price correctly
            'totalCashReceived' => $sales->sum('cash'), // Sum the total cash correctly
            'totalDue' => $totalDue, // Sum the total due correctly, including soFarDue
        ]);
    }

    private function calculateSoFarDue()
    {
        // Get the start of the selected month
        $startOfMonth = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();

        // Sum the due_amount of all records before the start of the selected month
        $this->soFarDue = HeadOfficeDue::where('date', '<', $startOfMonth)
                                        ->sum('due_amount');
    }



    private function getSalesData()
    {
        // Retrieve sales data for the selected month and year
        $sales = HeadOfficeSale::whereYear('date', $this->selectedYear)
                               ->whereMonth('date', $this->selectedMonth)
                               ->get();

        // Group sales by date and sum up the data for the same day
        return $sales->groupBy('date')->map(function ($salesOnDate) {
            $totalSets = $salesOnDate->sum('sets');
            $firstSale = $salesOnDate->first(); // Get the first sale of the day for the set price
            $totalPrice = $salesOnDate->sum('total_price');
            $totalCash = $salesOnDate->sum('cash');
            $totalDue = $salesOnDate->sum(fn($sale) => $sale->total_price - $sale->cash);

            return [
                'date' => $firstSale->date,
                'sets' => $totalSets,
                'set_price' => $firstSale->per_set_price, // Use the set price from the first record
                'price' => $totalPrice,
                'cash' => $totalCash,
                'due' => $totalDue,
            ];
        })->values();
    }


    public function downloadPdf()
    {
        $sales = $this->getSalesData();
        $completeMonth = $this->getCompleteMonth();

        // Calculate the total due by adding the soFarDue to the sum of the due amounts from the sales data
        $totalDue = $this->soFarDue + $sales->sum('due');

        $data = [
            'sales' => $sales,
            'completeMonth' => $completeMonth,
            'soFarDue' => $this->soFarDue,
            'totalSetPrice' => $sales->sum('sets'),
            'totalPrice' => $sales->sum('price'),
            'totalCashReceived' => $sales->sum('cash'),
            'totalDue' => $totalDue,
            'selectedMonth' => $this->selectedMonth,
            'selectedYear' => $this->selectedYear,
        ];

        $pdf = Pdf::loadView('pdf.head-office-sale-report', $data);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'head-office-sale-report-' . $this->selectedYear . '-' . $this->selectedMonth . '.pdf'
        );
    }



    private function getCompleteMonth()
    {
        $daysInMonth = Carbon::create($this->selectedYear, $this->selectedMonth)->daysInMonth;

        return collect(range(1, $daysInMonth))->map(function ($day) {
            return Carbon::create($this->selectedYear, $this->selectedMonth, $day);
        });
    }
}
