<?php

namespace App\Livewire;

use Livewire\Component;

class StorePage extends Component
{
    public $stores = [];

    public function mount()
    {
        $this->stores = [
            [
                'city' => 'Jakarta Selatan',
                'name' => 'Afilia Flagship Store - SCBD',
                'address' => 'District 8, Sudirman Central Business District (SCBD), Jend. Sudirman Kav 52-53, Jakarta Selatan.',
                'hours' => 'Senin - Minggu: 10.00 - 22.00 WIB',
                'phone' => '(021) 1234 5678'
            ],
            [
                'city' => 'Bandung',
                'name' => 'Afilia Boutique - Paris Van Java',
                'address' => 'Paris Van Java Mall, Resort Level RL-08, Jl. Sukajadi No. 131-139, Cipedes, Bandung.',
                'hours' => 'Senin - Minggu: 10.00 - 22.00 WIB',
                'phone' => '(022) 8765 4321'
            ],
            [
                'city' => 'Surabaya',
                'name' => 'Afilia Experience Store - Tunjungan Plaza',
                'address' => 'Tunjungan Plaza 6, 4th Floor Unit 12, Jl. Basuki Rahmat No. 8-12, Surabaya.',
                'hours' => 'Senin - Minggu: 10.00 - 22.00 WIB',
                'phone' => '(031) 5678 1234'
            ]
        ];
    }

    public function render()
    {
        return view('livewire.store-page')->layout('layouts.app');
    }
}
