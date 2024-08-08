<?php

namespace App\Livewire\Report;

use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class ExpenseReportComponent extends Component
{
    public $year;
    public $month;
    public $expenses;
    public $monthNameYear;
    public $totalAmount;

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
        // Get expenses data for the selected month
        $this->expenses = Expense::whereYear('date', $this->year)
                        ->whereMonth('date', $this->month)
                        ->get();

        // Calculate total amount
        $this->totalAmount = $this->expenses->sum('amount');

        // Format month and year
        $this->monthNameYear = Carbon::createFromDate($this->year, $this->month, 1)->format('F Y');
    }

    public function downloadPdf()
    {
        $data = [
            'expenses' => $this->expenses,
            'monthYear' => $this->monthNameYear,
            'totalAmount' => $this->totalAmount
        ];

        $pdf = Pdf::loadView('pdf.expense-report', $data);

        return response()->streamDownload(
            fn() => print($pdf->output()), 
            'expense-report-'.$this->monthNameYear.'.pdf'
        );
    }

    public function render()
    {
        return view('livewire.report.expense-report-component');
    }

}
