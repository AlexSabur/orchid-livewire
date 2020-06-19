<?php

namespace AlexSabur\OrchidLivewire;

use AlexSabur\OrchidLivewire\Layouts\Livewire as LivewireLayout;
use Closure;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Orchid\Screen\AsSource;
use Orchid\Screen\Layout;
use Orchid\Screen\Repository;
use Orchid\Screen\TD;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__.'/../config/orchid-livewire.php';

    public function boot()
    {
        $this->registerViewComposer()
            ->redisterTDMacro()
            ->redisterLayoutMacro();

        $this->publishes([
            self::CONFIG_PATH => config_path('orchid-livewire.php'),
        ], 'config');
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
        Layout::macro('livewire', function (string $component) {
            return new class($component) extends LivewireLayout {
            };
        });

        return $this;
    }

    protected function redisterTDMacro()
    {
        TD::macro('livewire', function (string $component, Closure $handler = null) {
            /** @var TD $this */
            $this->render(function ($source) use ($component, $handler) {
                /** @var Repository|AsSource $source */

                return view('livewire::mount-component', [
                    'name' => $component,
                    'params' => $handler ? $handler($source) : [
                        str_replace('.', '', $this->name) => $source->getContent($this->name),
                    ],
                ]);
            });

            return $this;
        });

        return $this;
    }
}
