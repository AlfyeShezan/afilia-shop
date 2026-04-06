<?php

namespace App\Livewire;

use Livewire\Component;

class ContactPage extends Component
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'subject' => 'required',
        'message' => 'required|min:10',
    ];

    public function submit()
    {
        $this->validate();

        // Simulate sending email/message
        sleep(1);

        session()->flash('success', 'Pesan Anda telah berhasil dikirim! Tim kami akan menghubungi Anda segera.');

        $this->reset(['name', 'email', 'subject', 'message']);
    }

    public function render()
    {
        return view('livewire.contact-page')->layout('layouts.app');
    }
}
