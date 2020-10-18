<?php

declare(strict_types=1);

namespace AlexSabur\OrchidLivewire;

use AlexSabur\OrchidLivewire\Layouts\Livewire as LivewireLayout;
use Closure;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Orchid\Screen\AsSource;
use Orchid\Screen\LayoutFactory;
use Orchid\Screen\Repository;
use Orchid\Screen\TD;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__.'/../config/orchid-livewire.php';

    public function boot()
    {
        $this->registerViewComposer()
            ->redisterTDMacro()
            ->registerViews()
            ->redisterLayoutMacro();

        $this->publishes([
            self::CONFIG_PATH => config_path('orchid-livewire.php'),
        ], 'config');
    }

    protected function registerViews()
    {
        $this->loadViewsFrom(
            __DIR__.DIRECTORY_SEPARATOR.'views',
            'orchid-livewire'
        );

        return $this;
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'orchid-livewire'
        );
    }

    protected function registerViewComposer()
    {
        View::composer('platform::app', function (\Illuminate\View\View $view) {
            $config = $this->app['config']->get('orchid-livewire');

            if ($config['assets']) {
                $options = $config['options'];
                $view->getFactory()
                    ->startPush('scripts', Livewire::scripts($options['scripts']));
                $view->getFactory()
                    ->startPush('stylesheets', Livewire::styles($options['styles']));
            }
        });

        return $this;
    }

    protected function redisterLayoutMacro()
    {
        LayoutFactory::macro('livewire', function (string $component) {
            return new class($component) extends LivewireLayout {
            };
        });

        return $this;
    }

    protected function redisterTDMacro()
    {
        TD::macro('livewire', function (string $component, Closure $handler = null, Closure $key = null) {
            /** @var TD $this */
            $this->render(function ($source) use ($component, $handler, $key) {
                /** @var Repository|AsSource $source */

                return view('orchid-livewire::mount-component', [
                    'name' => $component,
                    'params' => $handler ? $handler($source) : [
                        str_replace('.', '', $this->name) => $source->getContent($this->name),
                    ],
                    'key' => $key ? $key($source) : null
                ]);
            });

            return $this;
        });

        return $this;
    }
}
