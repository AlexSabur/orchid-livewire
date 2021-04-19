<?php

declare(strict_types=1);

namespace Tests\Unit;

use Livewire\Component;
use Livewire\Livewire;
use Orchid\Platform\Models\User;
use Orchid\Screen\Repository;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class MacroTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        Livewire::component('tests.unit.user-component', UserComponent::class);

        $this->user = User::factory()->make();
    }

    public function testLayout(): void
    {
        $data = new Repository([
            'user' => $this->user,
        ]);

        $view = Layout::livewire(UserComponent::class)->build($data);

        $this->assertStringContainsString("Email: {$this->user->email}", (string) $view);
    }

    public function testTd(): void
    {
        $view = TD::make()
            ->livewire(UserComponent::class, 'user', function (User $user) {
                return "key-$user->id";
            })
            ->buildTd($this->user);

        $this->assertStringContainsString("Email: {$this->user->email}", (string) $view);
    }

    public function testSight(): void
    {
        $layout = Layout::legend('user', [
            Sight::make()->livewire(UserComponent::class, 'user', function (User $user) {
                return "key-$user->id";
            }),
        ]);

        $view = $layout->build(new Repository([
            'user' => $this->user,
        ]));

        $this->assertStringContainsString("Email: {$this->user->email}", (string) $view);
    }
}

class UserComponent extends Component
{
    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('user-component');
    }
}
