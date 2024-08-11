<?php

namespace App\Livewire\Report;

use App\Models\BranchSale;
use App\Models\HeadOfficeSale;
use App\Models\Money;
use App\Models\Stock;
use App\Models\Expense; // Import the Expense model
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class BankBalanceReport extends Component
{
    use WithPagination;

    public $month;
    public $year;
    public $data;
    public $previousMonthData;
    public $initialBalance;

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        $this->initialBalance = Money::where('type', 'cash_in')->sum('amount');
        $this->loadData();
    }

    public function updated($propertyName)
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Initialize the selected month start and end dates
        $startDate = Carbon::create($this->year, $this->month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        // Initialize date ranges for the selected month
        $dates = [];
        $currentDate = $startDate->copy(); // Use copy to avoid modifying the original startDate
        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Data for all time before the selected month
        $endOfPreviousMonth = $startDate->copy()->subMonth()->endOfMonth();
        $allTimeBeforeSummary = [
            'cash_receive' => 0,
            'purchase_sets' => 0,
            'purchase_price' => 0,
            'expenses' => 0
        ];

        // Calculate totals before the end of the previous month
        $allTimeBeforeSummary['cash_receive'] = HeadOfficeSale::whereDate('date', '<=', $endOfPreviousMonth)
            ->sum('cash') + BranchSale::whereDate('date', '<=', $endOfPreviousMonth)->sum('cash');
        $allTimeBeforeSummary['purchase_sets'] = Stock::whereDate('date', '<=', $endOfPreviousMonth)->sum('sets');
        $allTimeBeforeSummary['purchase_price'] = Stock::whereDate('date', '<=', $endOfPreviousMonth)->sum('total_price');
        $allTimeBeforeSummary['expenses'] = Expense::whereDate('date', '<=', $endOfPreviousMonth)->sum('amount');

        $previousMonthBalance = $this->initialBalance
            + $allTimeBeforeSummary['cash_receive']
            - $allTimeBeforeSummary['purchase_price']
            - $allTimeBeforeSummary['expenses'];

        // Set the previous month data to be displayed in the view
        $this->previousMonthData = [
            'cash_receive' => $allTimeBeforeSummary['cash_receive'],
            'purchase_sets' => $allTimeBeforeSummary['purchase_sets'],
            'purchase_price' => $allTimeBeforeSummary['purchase_price'],
            'expenses' => $allTimeBeforeSummary['expenses'],
            'balance' => $previousMonthBalance // Balance at the end of the previous month
        ];

        // Data for the selected month
        $currentMonthData = [];
        $balance = $previousMonthBalance; // Start with the end-of-previous-month balance
        foreach ($dates as $dateStr) {
            $cashReceive = HeadOfficeSale::whereDate('date', $dateStr)->sum('cash')
                + BranchSale::whereDate('date', $dateStr)->sum('cash');
            $purchasePrice = Stock::whereDate('date', $dateStr)->sum('total_price');
            $purchaseSets = Stock::whereDate('date', $dateStr)->sum('sets');
            $expenses = Expense::whereDate('date', $dateStr)->sum('amount'); // Calculate expenses for the date

            // Calculate available balance for this date
            $balance += $cashReceive - $purchasePrice - $expenses;

            $currentMonthData[] = [
                'date' => $dateStr,
                'cash_receive' => $cashReceive,
                'purchase_sets' => $purchaseSets,
                'purchase_price' => $purchasePrice,
                'expenses' => $expenses, // Include expenses
                'available_balance' => $balance,
            ];
        }

        $this->data = $currentMonthData;
    }

    public function render()
    {
        return view('livewire.report.bank-balance-report');
    }

    public function downloadPdf()
    {
        // Ensure month is an integer and format it properly
        $monthFormatted = (int) $this->month;
        $monthName = Carbon::create()->month($monthFormatted)->format('F');

        // Ensure year is set correctly
        $year = $this->year;

        // Prepare the data for the PDF
        $data = [
            'previousMonthData' => $this->previousMonthData,
            'data' => $this->data,
            'initialBalance' => $this->initialBalance,
            'month' => $monthName,
            'year' => $year,
        ];

        // Load the view and generate the PDF
        $pdf = Pdf::loadView('pdf.bank-balance-report', $data);

        // Stream the PDF to the browser
        return response()->stream(
            function () use ($pdf) {
                echo $pdf->output();
            },
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="bank-balance-report-' . $monthName . '-' . $year . '.pdf"',
            ]
        );
    }

}
