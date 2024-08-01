<?php

namespace App\Livewire;

use App\Models\Balance;
use Livewire\Component;
use App\Models\Money;
use Carbon\Carbon;

class MoneyComponent extends Component
{
    public $date;
    public $amount;
    public $type;
    public $details;
    public $totalBalance;
    public $transactions;

    protected $rules = [
        'date' => 'required|date_format:Y-m-d',
        'amount' => 'required|numeric',
        'type' => 'required|string|in:cash_in,cash_out',
        'details' => 'nullable|string',
    ];

    public function mount()
    {
        // Initialize total balance and fetch transactions on component mount
        $this->totalBalance = $this->getTotalBalance();
        $this->transactions = Money::latest()->get();

        // Set default date to today's date
         $this->date = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        // Render the Livewire component view with transactions
        return view('livewire.money-component', [
            'transactions' => $this->transactions,
        ]);
    }

    public function saveTransaction()
    {
        // Validate the form input
      
        $this->validate();

        // Create a new Money record
        $money = Money::create([
            'date' => $this->date,
            'amount' => $this->amount,
            'type' => $this->type,
            'details' => $this->details,
        ]);

        // Update or create the total balance based on transaction type
        if ($this->type === 'cash_in') {
            Balance::updateOrCreate([], ['total_balance' => $this->totalBalance + $this->amount]);
        } elseif ($this->type === 'cash_out') {
            Balance::updateOrCreate([], ['total_balance' => $this->totalBalance - $this->amount]);
        }

        // Update total balance property
        $this->totalBalance = $this->getTotalBalance();

        // Fetch updated transactions
        $this->transactions = Money::latest()->get();

        sweetalert()->success('Transaction saved successfully!');

        
        $this->reset(['date', 'amount', 'type', 'details']);
    }

    protected function getTotalBalance()
    {
        // Retrieve the total balance from the Balance model
        return Balance::sum('total_balance');
    }
}
