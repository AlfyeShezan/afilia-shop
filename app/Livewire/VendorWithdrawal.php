<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;

class VendorWithdrawal extends Component
{
    public $vendor;
    public $amount;
    public $bank_name;
    public $account_number;
    public $account_holder;
    public $withdrawals = [];

    protected $rules = [
        'amount' => 'required|numeric|min:10',
        'bank_name' => 'required|string',
        'account_number' => 'required|string',
        'account_holder' => 'required|string',
    ];

    public function mount()
    {
        $this->vendor = Auth::user()->vendor;
        if (!$this->vendor) return redirect()->route('home');
        
        $this->loadWithdrawals();
    }

    public function loadWithdrawals()
    {
        $this->withdrawals = Withdrawal::where('vendor_id', $this->vendor->id)
            ->latest()
            ->get();
    }

    public function requestWithdrawal()
    {
        $this->validate();

        if ($this->amount > $this->vendor->balance) {
            $this->addError('amount', 'Saldo tidak mencukupi.');
            return;
        }

        Withdrawal::create([
            'vendor_id' => $this->vendor->id,
            'amount' => $this->amount,
            'bank_info' => [
                'bank_name' => $this->bank_name,
                'account_number' => $this->account_number,
                'account_holder' => $this->account_holder,
            ],
            'status' => 'pending',
        ]);

        // Deduct balance immediately or hold it
        $this->vendor->decrement('balance', $this->amount);

        $this->reset(['amount', 'bank_name', 'account_number', 'account_holder']);
        $this->loadWithdrawals();

        $this->dispatch('notify', [
            'message' => 'Permintaan penarikan dana berhasil dikirim!',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.vendor-withdrawal');
    }
}
