<?php

declare(strict_types=1);

namespace AlexSabur\OrchidLivewire;

use AlexSabur\OrchidLivewire\Layouts\Livewire as LivewireLayout;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Orchid\Screen\AsSource;
use Orchid\Screen\Cell;
use Orchid\Screen\LayoutFactory;
use Orchid\Screen\Repository;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Dashboard;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/orchid-livewire.php';
    const PUBLIC_PATH = __DIR__ . '/../public';

    public function boot()
    {
        $this->registerViewComposer()
            ->redisterCellMacro()
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
            __DIR__ . DIRECTORY_SEPARATOR . 'views',
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
        LayoutFactory::macro(
            'livewire',
            /**
             * @param string $component
             * @param Closure|string|null $key
             *
             * @return LivewireLayout
             */
            function (string $component, $key = null) {
                return new class($component, $key) extends LivewireLayout
                {
                };
            }
        );

        return $this;
    }

    protected function redisterCellMacro()
    {
        Cell::macro(
            'livewire',
            /**
             * @param string $component
             * @param Closure|string|null $name
             * @param Closure|string|null $key
             *
             * @return static
             */
            function (string $component, $name = null, $key = null) {
                /** @var Cell $this */
                $this->render(function ($source) use ($component, $name, $key) {
                    /** @var Repository|AsSource $source */

                    if (is_string($name)) {
                        $params = [$name => $source];
                    } elseif (is_callable($name)) {
                        $params = $name($source);
                    } else {
                        $params = [
                            str_replace('.', '', $this->name) => $source->getContent($this->name),
                        ];
                    }

                    if (is_string($key)) {
                        $key = $source->getContent($key);
                    } elseif (is_callable($key)) {
                        $key = $key($source);
                    } else {
                        $key = null;
                    }

                    $view = view('orchid-livewire::mount-component', [
                        'name' => $component,
                        'params' => $params,
                        'key' => $key,
                    ]);

                    if ($this instanceof TD) {
                        return $view;
                    }

                    return $view->render();
                });

                return $this;
            }
        );

        return $this;
    }

    public static function getTagScript()
    {
        $src = orchid_mix('/js/livewire-turbolinks.js', 'orchid-livewire');

        return "<script src=\"$src\" data-turbo-eval=\"false\"></script>";
    }
}
