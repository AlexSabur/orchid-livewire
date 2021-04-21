<?php

declare(strict_types=1);

namespace Tests\Unit;

use Orchid\Screen\Repository;
use Orchid\Support\Facades\Layout;

class LayoutTest extends TestCase
{
    public function testLayout(): void
    {
        $data = new Repository([
            'user' => $this->user,
        ]);

        $view = Layout::livewire('tests.components.user-component')->build($data);

        $this->assertStringContainsString("Id: {$this->user->id}", (string) $view);
        $this->assertStringContainsString("Email: {$this->user->email}", (string) $view);
    }

    public function testEmpty(): void
    {
        $data = new Repository([
            'bar' => 'i bazzz',
            'baz' => 'i love bar',
        ]);

        $view = (string) Layout::livewire('tests.components.bar-baz-component')->empty()->build($data);

        $this->assertStringContainsString('bar: null;', $view);
        $this->assertStringContainsString('baz: null;', $view);
    }

    public function testOnly(): void
    {
        $data = new Repository([
            'bar' => 'i bazzz',
            'baz' => 'i love bar',
        ]);

        $view = (string) Layout::livewire('tests.components.bar-baz-component')->only(['bar'])->build($data);

        $this->assertStringContainsString('bar: i bazzz;', $view);
        $this->assertStringContainsString('baz: null;', $view);
    }

    public function testExcept(): void
    {
        $data = new Repository([
            'bar' => 'i bazzz',
            'baz' => 'i love bar',
        ]);

        $view = (string) Layout::livewire('tests.components.bar-baz-component')->except(['bar'])->build($data);

        $this->assertStringContainsString('bar: null;', $view);
        $this->assertStringContainsString('baz: i love bar;', $view);
    }
}
