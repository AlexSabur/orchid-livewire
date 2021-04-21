<?php

declare(strict_types=1);

namespace Tests\Unit;

use Orchid\Platform\Models\User;
use Orchid\Screen\Sight;
use Tests\Components\UserEmailComponent;

class SightTest extends TestCase
{
    public function testWithAll(): void
    {
        $view = Sight::make('email')
            ->livewire('tests.components.user-component', 'user', function (User $user) {
                return "key-$user->id";
            })
            ->buildDd($this->user);

        $this->assertStringContainsString("Id: {$this->user->id}", (string) $view);
        $this->assertStringContainsString("Email: {$this->user->email}", (string) $view);
    }

    public function testWithClassName(): void
    {
        $view = Sight::make('email')
            ->livewire(UserEmailComponent::class)
            ->buildDd($this->user);

        $this->assertStringContainsString("Email: {$this->user->email}", (string) $view);
    }

    public function testWithoutNameAndKey(): void
    {
        $view = Sight::make('email')
            ->livewire('tests.components.user-email-component')
            ->buildDd($this->user);

        $this->assertStringContainsString("Email: {$this->user->email}", (string) $view);
    }

    public function testWithoutNameClosure(): void
    {
        $view = Sight::make('email')
            ->livewire('tests.components.user-email-component', function (User $user) {
                return [
                    'email' => $user->email,
                ];
            })
            ->buildDd($this->user);

        $this->assertStringContainsString("Email: {$this->user->email}", (string) $view);
    }

    public function testWithoutName(): void
    {
        $view = Sight::make('email')
            ->livewire('tests.components.user-email-component', null, function (User $user) {
                return "key-$user->id";
            })
            ->buildDd($this->user);

        $this->assertStringContainsString("Email: {$this->user->email}", (string) $view);
    }
}
