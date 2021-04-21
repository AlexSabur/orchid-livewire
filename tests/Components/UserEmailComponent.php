<?php

declare(strict_types=1);

namespace Tests\Components;

use Livewire\Component;

class UserEmailComponent extends Component
{
    public $email;

    public function mount($email)
    {
        $this->email = $email;
    }

    public function render()
    {
        return <<<'blade'
<div>
    Email: {{ $email }}
</div>
blade;
    }
}
