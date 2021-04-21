<?php

declare(strict_types=1);

namespace Tests\Components;

use Livewire\Component;

class BarBazComponent extends Component
{
    public $bar;
    public $baz;

    public function mount($bar = null, $baz = null)
    {
        $this->bar = $bar;
        $this->baz = $baz;
    }

    public function render()
    {
        return <<<'blade'
<div>
    bar: {{ $bar ?? 'null' }};
    baz: {{ $baz ?? 'null' }};
</div>
blade;
    }
}
