<?php

namespace App\Livewire;

use App\Models\Balance;
use App\Models\Branch;
use App\Models\BranchPrice;
use App\Models\BranchSale;
use App\Models\BranchSaleOutstanding;
use App\Models\OutstandingBalanceHistory;
use App\Models\Payment;
use App\Models\Stock;
use App\Models\TotalStock;
use Carbon\Carbon;
use Livewire\Component;

class BranchSaleComponent extends Component
{
    public $branches;
    public $branch_id;
    public $date;
    public $sets;
    public $perSetPrice;
    public $totalPrice;
    public $cash;
    public $extraMoney;
    public $branchSales;

    protected $rules = [
        'branch_id' => 'required|exists:branches,id',
        'date' => 'required|date_format:Y-m-d',
        'sets' => 'required|numeric|min:0.1',
        'cash' => 'required|numeric|min:0',
    ];


    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->branches = Branch::all();
        $branchSale = BranchSale::with('branch')->latest()->get();
        if ($branchSale) {
            $this->branchSales = $branchSale;
        }
    }

    public function render()
    {
        // Calculate extra money if cash and total price are set and numeric
        if (!is_null($this->cash) && is_numeric($this->cash) && !is_null($this->totalPrice)) {
            $this->extraMoney = $this->cash - $this->totalPrice;
        } else {
            $this->extraMoney = null;
        }
    
        return view('livewire.branch-sale-component');
    }
    

    public function updatedSets()
    {
        $this->calculateTotalPrice();
    }

    public function calculateTotalPrice()
    {
        if ($this->sets) {
            $branchPrice = $this->getBranchPrice();
            if ($branchPrice) {
                $this->perSetPrice = $branchPrice->price;
                $this->totalPrice = $this->sets * $this->perSetPrice;
            } else {
                $this->perSetPrice = null;
                $this->totalPrice = null;
            }
        } else {
            $this->perSetPrice = null;
            $this->totalPrice = null;
        }
    }

    public function saveSale()
    {
        // Begin transaction to ensure data consistency
        \DB::transaction(function () {
            // Calculate total amount and extra money
            $totalAmount = $this->totalPrice;
            $extraMoney = $this->cash - $totalAmount;
            $totalAmount = $this->totalPrice;
            $cashReceived = $this->cash;

             // Calculate outstanding balance
            $outstandingBalance = $totalAmount - $cashReceived;
            $extraMoney = $cashReceived > $totalAmount ? $cashReceived - $totalAmount : 0;

            // Ensure outstanding balance is not negative
            if ($outstandingBalance < 0) {
                $outstandingBalance = 0;
            }

            // Create branch sale record
            $branchSale = BranchSale::create([
                'branch_id' => $this->branch_id,
                'date' => $this->date,
                'sets' => $this->sets,
                'per_set_price' => $this->perSetPrice,
                'total_price' => $this->totalPrice,
                'cash' => $this->cash,
            ]);

            // Record payment
            Payment::create([
                'branch_id' => $this->branch_id,
                'date' => $this->date,
                'amount' => $this->cash,
            ]);

            $piecesSold = $this->sets * 3;

            // Update stock (example decrement, adjust based on your stock logic)
            $totalStock = TotalStock::find(1); // Assuming you want to update the row with id = 1
            $totalStock->decrement('total_sets', $this->sets);
            $totalStock->decrement('total_pieces', $piecesSold);

            // Update balance (example update, adjust based on your balance logic)
            $balance = Balance::first();
            $balance->update(['total_balance' => $balance->total_balance + $this->cash]);

            // Update branch outstanding_balance based on extra money
            $branch = Branch::find($this->branch_id);

           BranchSaleOutstanding::create([
                'branch_sale_id' => $branchSale->id,
                'branch_id' => $this->branch_id,
                'date' => $this->date,
                'outstanding_balance' => $outstandingBalance,
                'extra_money' => $extraMoney,
            ]);   
    

            if ($extraMoney > 0) {
                // Extra cash received
                sweetalert()->success('Extra cash received: $' . number_format($extraMoney, 2));
                $branch->update(['outstanding_balance' => $branch->outstanding_balance - $extraMoney]);
            } elseif ($extraMoney < 0) {
                // Due amount received
                sweetalert()->success('Due amount received: $' . number_format(-$extraMoney, 2));
                $branch->update(['outstanding_balance' => $branch->outstanding_balance + abs($extraMoney)]);
            } else {
                // No extra cash or due amount
                sweetalert()->success('Exact amount received (no extra or due)');
            }

            // Save or update the outstanding balance at the end of the month using the branch sale date
            $saleDate = Carbon::parse($this->date);
            if ($saleDate->isSameDay($saleDate->endOfMonth())) {
                $outstandingBalanceHistory = OutstandingBalanceHistory::firstOrNew([
                    'branch_id' => $this->branch_id,
                    'date' => $saleDate->format('Y-m-d'),
                ]);

                $outstandingBalanceHistory->outstanding_balance = $branch->outstanding_balance;
                $outstandingBalanceHistory->save();
            }

        });

        // Reset input fields
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->branch_id = null;
        $this->date = Carbon::today()->format('Y-m-d');
        $this->sets = null;
        $this->perSetPrice = null;
        $this->totalPrice = null;
        $this->cash = null;
    }

    private function getBranchPrice()
    {
        return BranchPrice::first();
    }

}
