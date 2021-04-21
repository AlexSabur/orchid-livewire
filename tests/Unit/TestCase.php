<?php

namespace Tests\Unit;

use Orchestra\Testbench\TestCase as BaseTestCase;
use AlexSabur\OrchidLivewire\ServiceProvider;
use Orchid\Platform\Providers\PlatformServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider;
use Orchid\Platform\Models\User;
use Orchid\Platform\Providers\FoundationServiceProvider;
use Tests\Components\BarBazComponent;
use Tests\Components\UserComponent;
use Tests\Components\UserEmailComponent;

class TestCase extends BaseTestCase
{
    protected $user;

    protected function setUp(): void
    {
        $this->beforeApplicationDestroyed(function () {
            Artisan::call('view:clear');
        });

        $this->afterApplicationCreated(function () {
            Artisan::call('view:clear');

            if (!File::exists(app_path('Http/Livewire'))) {
                File::makeDirectory(app_path('Http/Livewire'), 0755, true);
            }
        });

        parent::setUp();

        $this->loadLaravelMigrations();

        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => realpath(base_path('vendor/orchid/platform/database/migrations/migrations')),
        ]);

        Factory::guessFactoryNamesUsing(function ($factory) {
            $factoryBasename = class_basename($factory);

            return "Tests\Factories\\$factoryBasename" . 'Factory';
        });

        Livewire::component('tests.components.user-component', UserComponent::class);
        Livewire::component('tests.components.user-email-component', UserEmailComponent::class);
        Livewire::component('tests.components.bar-baz-component', BarBazComponent::class);

        $this->user = User::factory()->make();
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('view.paths', [
            __DIR__.'/views',
            resource_path('views'),
        ]);

        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    public function makeACleanSlate()
    {
        Artisan::call('view:clear');
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            FoundationServiceProvider::class,
            PlatformServiceProvider::class,
            ServiceProvider::class
        ];
    }
}
