<?php

namespace Matheusmarnt\Scoutify\Livewire;

use Livewire\Component;

class Modal extends Component
{
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('scoutify::livewire.modal');
    }
}
