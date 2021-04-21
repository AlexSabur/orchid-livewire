<?php

declare(strict_types=1);

namespace Tests\Components;

use Livewire\Component;
use Orchid\Platform\Models\User;

class UserComponent extends Component
{
    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return <<<'blade'
<div>
    Id: {{ $user->id }};
    Email: {{ $user->email }};
</div>
blade;
    }
}
