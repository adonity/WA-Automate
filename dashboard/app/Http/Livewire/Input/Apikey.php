<?php

namespace App\Http\Livewire\Input;

use Livewire\Component;

class Apikey extends Component
{
    public $attributes, $key, $show = false;

    public function togglefield(){
        $this->show = !$this->show;
    }

    public function render()
    {
        $key = $this->key;
        $show = $this->show;
        return view('livewire.input.apikey', compact('key', 'show'));
    }
}
