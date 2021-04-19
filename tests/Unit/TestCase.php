<?php

namespace Tests\Unit;

use Orchestra\Testbench\TestCase as BaseTestCase;
use AlexSabur\OrchidLivewire\ServiceProvider;
use Orchid\Platform\Providers\PlatformServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Livewire\LivewireServiceProvider;
use Orchid\Platform\Providers\FoundationServiceProvider;

class TestCase extends BaseTestCase
{
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

        /* Install application */
        $this->loadLaravelMigrations();

        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => realpath(base_path('vendor/orchid/platform/database/migrations/migrations')),
        ]);

        Factory::guessFactoryNamesUsing(function ($factory) {
            $factoryBasename = class_basename($factory);

            return "Tests\Factories\\$factoryBasename" . 'Factory';
        });
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

        // File::makeDirectory($this->livewireViewsPath(), 0755, true);
        // File::makeDirectory($this->livewireClassesPath(), 0755, true);
        // File::makeDirectory($this->livewireTestsPath(), 0755, true);
        // if (File::exists($this->livewireViewsPath())) {
        // File::deleteDirectory($this->livewireViewsPath());
        // }
        // if (File::exists($this->livewireClassesPath())) {
        // File::deleteDirectory($this->livewireClassesPath());
        // }
        // if (File::exists($this->livewireTestsPath())) {
        // File::deleteDirectory($this->livewireTestsPath());
        // }
        // File::delete(app()->bootstrapPath('cache/livewire-components.php'));
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

    // protected function resolveApplicationHttpKernel($app)
    // {
    //     $app->singleton('Illuminate\Contracts\Http\Kernel', 'Tests\HttpKernel');
    // }

    protected function livewireClassesPath($path = '')
    {
        return realpath(app_path('Http/Livewire' . ($path ? '/' . $path : '')));
    }

    protected function livewireViewsPath($path = '')
    {
        return resource_path('views') . '/livewire' . ($path ? '/' . $path : '');
    }

    protected function livewireTestsPath($path = '')
    {
        return base_path('tests/Feature/Livewire' . ($path ? '/' . $path : ''));
    }
}
