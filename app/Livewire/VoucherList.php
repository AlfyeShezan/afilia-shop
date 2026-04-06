<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Voucher;

class VoucherList extends Component
{
    public $vouchers;
    public string $voucherCode = '';
    public string $message = '';
    public string $messageType = 'success';

    public function mount(): void
    {
        $this->loadVouchers();
    }

    private function loadVouchers(): void
    {
        $this->vouchers = Voucher::active()->latest()->get();
    }

    public function claimVoucher(): void
    {
        $this->message = '';

        if (empty(trim($this->voucherCode))) {
            $this->message = 'Silakan masukkan kode voucher terlebih dahulu.';
            $this->messageType = 'error';
            return;
        }

        $voucher = Voucher::where('code', strtoupper(trim($this->voucherCode)))->first();

        if (!$voucher) {
            $this->message = 'Kode voucher tidak ditemukan.';
            $this->messageType = 'error';
            $this->voucherCode = '';
            return;
        }

        if (!$voucher->isValid()) {
            $this->message = 'Voucher ini sudah tidak berlaku atau habis.';
            $this->messageType = 'error';
            $this->voucherCode = '';
            return;
        }

        // Voucher valid, inform user to use at checkout
        $this->message = 'Voucher "' . $voucher->code . '" ditemukan! Gunakan kode ini saat checkout.';
        $this->messageType = 'success';
        $this->voucherCode = '';
    }

    public function render()
    {
        return view('livewire.voucher-list')->layout('layouts.app');
    }
}
