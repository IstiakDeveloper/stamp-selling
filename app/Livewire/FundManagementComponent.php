<?php

namespace App\Livewire;

use App\Models\FundManagement;
use App\Models\TotalFund;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Component;

class FundManagementComponent extends Component
{
    public $date;
    public $amount;
    public $type;
    public $note; // Add note field

    public function mount()
    {
        $this->initializeTotalFund();
        $this->resetInput();
    }

    protected function rules()
    {
        return [
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'type' => ['required', ValidationRule::in(['fund_in', 'fund_out'])],
            'note' => 'nullable|string|max:255', // Validate note field
        ];
    }

    public function submit()
    {
        $this->validate($this->rules());

        $fund = FundManagement::create([
            'date' => $this->date,
            'amount' => $this->amount,
            'type' => $this->type,
            'note' => $this->note, // Save note
        ]);

        $this->updateTotalFund($fund);

        $this->resetInput();
        session()->flash('message', 'Fund recorded successfully!');
    }

    private function initializeTotalFund()
    {
        if (!TotalFund::exists()) {
            TotalFund::create(['total_fund' => 0]);
        }
    }

    private function updateTotalFund($fund)
    {
        $totalFund = TotalFund::first();

        if ($fund->type == 'fund_in') {
            $totalFund->total_fund += $fund->amount;
        } else {
            $totalFund->total_fund -= $fund->amount;
        }

        $totalFund->save();
    }

    private function resetInput()
    {
        $this->date = now()->toDateString();
        $this->amount = null;
        $this->type = null;
        $this->note = null; // Reset note field
    }

    public function render()
    {
        $totalFund = TotalFund::first()->total_fund;

        return view('livewire.fund-management-component', [
            'totalFund' => $totalFund,
        ]);
    }

}
