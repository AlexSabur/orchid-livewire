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
use Orchid\Support\Facades\Dashboard;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__.'/../config/orchid-livewire.php';
    const PUBLIC_PATH = __DIR__.'/../public';

    public function boot()
    {
        $this->registerViewComposer()
            ->redisterTDMacro()
            ->registerResources()
            ->registerViews()
            ->redisterLayoutMacro();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::CONFIG_PATH => config_path('orchid-livewire.php'),
            ], 'config');
        }
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

    protected function registerResources()
    {
        Dashboard::addPublicDirectory('orchid-livewire', static::PUBLIC_PATH);

        return $this;
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
            if ($config['turbolinks']) {
                $view->getFactory()
                    ->startPush('scripts', static::getTagScript());
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
                    'key' => $key ? $key($source) : null,
                ]);
            });

            return $this;
        });

        return $this;
    }

    public static function getTagScript()
    {
        $src = orchid_mix('/js/livewire-turbolinks.js', 'orchid-livewire');

        return "<script src=\"$src\" data-turbo-eval=\"false\"></script>";
    }
}
