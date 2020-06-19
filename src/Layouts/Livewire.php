<?php

namespace AlexSabur\OrchidLivewire\Layouts;

use Illuminate\Support\Arr;
use Orchid\Screen\Layouts\Base;
use Orchid\Screen\Repository;

abstract class Livewire extends Base
{
    /**
     * @var string
     */
    private $component;

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
    public function __construct(string $component)
    {
        $this->component = $component;
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
        if (! $this->checkPermission($this, $repository)) {
            return;
        }

        $params = $repository->toArray();

        if (count($this->only)) {
            $params = Arr::only($params, $this->only);
        }

        if (count($this->except)) {
            $params = Arr::except($params, $this->except);
        }

        return view('livewire::mount-component', [
            'name' => $this->component,
            'params' => $params,
        ]);
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
