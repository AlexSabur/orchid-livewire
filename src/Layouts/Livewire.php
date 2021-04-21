<?php

declare(strict_types=1);

namespace AlexSabur\OrchidLivewire\Layouts;

use Closure;
use Illuminate\Support\Arr;
use Orchid\Screen\Layout;
use Orchid\Screen\Repository;

abstract class Livewire extends Layout
{
    /**
     * @var string
     */
    private $component;

    /**
     * @var Closure|string|null
     */
    private $key = null;

    /**
     * @var bool
     */
    private $empty = false;

    /**
     * @var string[]
     */
    private $only = [];

    /**
     * @var string[]
     */
    private $except = [];

    /**
     * Livewire constructor.
     *
     * @param string $component
     */
    public function __construct(string $component, $key = null)
    {
        $this->component = $component;
        $this->key = $key;
    }

    /**
     * @param Repository $repository
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     *
     * @return mixed
     */
    public function build(Repository $repository)
    {
        if (! $this->isSee()) {
            return;
        }

        $params = [];

        if (! $this->empty) {
            $params = $repository->toArray();

            if (count($this->only)) {
                $params = Arr::only($params, $this->only);
            }

            if (count($this->except)) {
                $params = Arr::except($params, $this->except);
            }
        }

        $key = null;

        if (is_string($this->key)) {
            $key = $this->key;
        } elseif (is_callable($this->key)) {
            $key = with($params, $this->key);
        }

        return view('orchid-livewire::mount-component', [
            'name' => $this->component,
            'params' => $params,
            'key' => $key,
        ]);
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function empty($value = true)
    {
        $this->empty = $value;

        return $this;
    }

    /**
     * @param array|string $keys
     * @return $this
     */
    public function only($keys)
    {
        $this->only = (array) $keys;

        return $this;
    }

    /**
     * @param array|string $keys
     * @return $this
     */
    public function except($keys)
    {
        $this->except = (array) $keys;

        return $this;
    }
}
