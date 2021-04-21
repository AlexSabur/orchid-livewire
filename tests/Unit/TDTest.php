<?php

declare(strict_types=1);

namespace Tests\Unit;

use Orchid\Platform\Models\User;
use Orchid\Screen\TD;
use Tests\Components\UserEmailComponent;

class TDTest extends TestCase
{
    public function testWithAll(): void
    {
        $view = TD::make('email')
            ->livewire('tests.components.user-component', 'user', function (User $user) {
                return "key-$user->id";
            })
            ->buildTd($this->user);

        $this->assertStringContainsString("Id: {$this->user->id}", (string) $view);
        $this->assertStringContainsString("Email: {$this->user->email}", (string) $view);
    }

    public function testWithClassName(): void
    {
        $view = TD::make('email')
            ->livewire(UserEmailComponent::class)
            ->buildTd($this->user);

        $this->assertStringContainsString("Email: {$this->user->email}", (string) $view);
    }

    public function testWithoutNameAndKey(): void
    {
        $view = TD::make('email')
            ->livewire('tests.components.user-email-component')
            ->buildTd($this->user);

        $this->assertStringContainsString("Email: {$this->user->email}", (string) $view);
    }

    public function testWithoutNameClosure(): void
    {
        $view = TD::make('email')
            ->livewire('tests.components.user-email-component', function (User $user) {
                return [
                    'email' => $user->email,
                ];
            })
            ->buildTd($this->user);

        $this->assertStringContainsString("Email: {$this->user->email}", (string) $view);
    }

    public function testWithoutName(): void
    {
        $view = TD::make('email')
            ->livewire('tests.components.user-email-component', null, function (User $user) {
                return "key-$user->id";
            })
            ->buildTd($this->user);

        $this->assertStringContainsString("Email: {$this->user->email}", (string) $view);
    }
}
