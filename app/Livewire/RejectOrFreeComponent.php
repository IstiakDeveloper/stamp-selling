<?php

namespace App\Livewire;

use App\Models\RejectOrFree;
use App\Models\Stock;
use App\Models\TotalStock;
use Carbon\Carbon;
use Livewire\Component;

class RejectOrFreeComponent extends Component
{
    public $date;
    public $sets;
    public $purchase_price_per_set;
    public $purchase_price_total;
    public $note;

    protected $rules = [
        'date' => 'required|date_format:Y-m-d',
        'sets' => 'required|numeric|min:0.333',
        'purchase_price_per_set' => 'required|numeric|min:0', // New validation rule
        'note' => 'nullable|string',
    ];

    public function mount() {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->calculatePurchasePricePerSet();
        $this->calculatePurchasePriceTotal(); 
    }

    public function updated($propertyName) {
        if ($propertyName === 'sets') {
            $this->calculatePurchasePriceTotal();
        }
    }

public function calculatePurchasePricePerSet()
    {
        $totalSets = Stock::sum('sets');
        $totalPrice = Stock::sum('total_price');
        
        if ($totalSets > 0) {
            $this->purchase_price_per_set = $totalPrice / $totalSets;
        } else {
            $this->purchase_price_per_set = 0;
        }

        $this->calculatePurchasePriceTotal();
    }

    public function calculatePurchasePriceTotal()
    {
        if ($this->sets && $this->purchase_price_per_set) {
            $this->purchase_price_total = $this->sets * $this->purchase_price_per_set;
        } else {
            $this->purchase_price_total = 0;
        }
    }

    public function saveTransaction()
    {
        $this->validate();

        RejectOrFree::create([
            'date' => $this->date,
            'sets' => $this->sets,
            'purchase_price_per_set' => $this->purchase_price_per_set,
            'purchase_price_total' => $this->purchase_price_total,
            'note' => $this->note,
        ]);

        // Decrease stock by the number of sets
        $piecesToRemove = $this->sets * 3; // Assuming 1 set = 3 pcs
        $totalStock = TotalStock::first();

        $totalStock->decrement('total_sets', $this->sets);
        $totalStock->decrement('total_pieces', $piecesToRemove);

        sweetalert()->success('Reject or free transaction recorded successfully.');

        // Reset input fields
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->date = null;
        $this->sets = null;
        $this->purchase_price_per_set = null; // Reset the new field
        $this->note = null;
    }

    public function render()
    {
        $rejectOrFrees = RejectOrFree::latest()->get();
        return view('livewire.reject-or-free-component', [
            'rejectOrFrees' => $rejectOrFrees,
        ]);
    }

}
