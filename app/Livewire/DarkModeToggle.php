<?php

namespace App\Livewire;

use Livewire\Component;

class DarkModeToggle extends Component
{
    public $isDark = false;

    public function mount()
    {
        // localStorage에서 다크 모드 상태를 가져오거나 시스템 설정을 따름
        $this->isDark = session('dark_mode', false);
    }

    public function toggle()
    {
        $this->isDark = !$this->isDark;
        session(['dark_mode' => $this->isDark]);
        
        $this->dispatch('dark-mode-changed', $this->isDark);
    }

    public function render()
    {
        return view('livewire.dark-mode-toggle');
    }
}