<?php

namespace App\Livewire\Chat;

use App\Models\User;
use Livewire\Component;

class Conversations extends Component
{

    public $users;

    public function mount(){
        $this->users = User::where('role', '!=', 'admin')->get();
    }

    public function render()
    {
        return view('livewire.chat.conversations');
    }
}
