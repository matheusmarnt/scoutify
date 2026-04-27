<?php

namespace Matheusmarnt\Scoutify\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Modal extends Component
{
    public function render(): View
    {
        return view('scoutify::livewire.modal');
    }
}
