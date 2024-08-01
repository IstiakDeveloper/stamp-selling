<?php

namespace App\Livewire;

use App\Models\Balance;
use App\Models\HeadOfficeSale;
use App\Models\Payment;
use App\Models\Stock;
use App\Models\TotalStock;
use Carbon\Carbon;
use Livewire\Component;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;

class HeadOfficeSaleComponent extends Component
{
    public $date;
    public $sets;
    public $per_stamp_price;
    public $cash;

    protected $rules = [
        'date' => 'required|date_format:Y-m-d',
        'sets' => 'required|numeric|min:0.1',
        'per_stamp_price' => 'required|numeric|min:0',
        'cash' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        $transactions = HeadOfficeSale::latest()->get();
        return view('livewire.head-office-sale-component', [
            'transactions' => $transactions,
            'totalPrice' => $this->calculateTotalPrice(),
        ]);
    }

    public function saveSale()
    {
        $this->validate();

        // Calculate total price based on sets and per_stamp_price
        $totalPrice = $this->sets * $this->per_stamp_price;

        // Create Head Office Sale record
        $headOfficeSale = HeadOfficeSale::create([
            'date' => $this->date,
            'sets' => $this->sets,
            'per_set_price' => $this->per_stamp_price,
            'total_price' => $totalPrice,
            'cash' => $this->cash,
        ]);

        // Record payment
        Payment::create([
            'date' => $this->date,
            'amount' => $this->cash,
        ]);

        $piecesSold = $this->sets * 3;
        $totalStock = TotalStock::find(1);
        $totalStock->decrement('total_sets', $this->sets);
        $totalStock->decrement('total_pieces', $piecesSold);

        $balance = Balance::first();
        $balance->update(['total_balance' => $balance->total_balance + $this->cash]);

        // Reset input fields
        $this->resetInputFields();

        sweetalert()->success('Head office sale recorded successfully)');
    }

    private function resetInputFields()
    {
        $this->date = null;
        $this->sets = null;
        $this->per_stamp_price = null;
        $this->cash = null;
    }

    public function calculateTotalPrice()
    {
        if (!is_null($this->sets) && !is_null($this->per_stamp_price)) {
            return $this->sets * $this->per_stamp_price;
        }

        return null;
    }
}
