<?php

// app/Http/Livewire/ShopComponent.php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Shop;

class ShopComponent extends Component
{
    public $shops;

    public function mount()
    {
        $this->shops = Shop::all();
    }

    public function render()
    {
        return view('livewire.shop-component');
    }
}
