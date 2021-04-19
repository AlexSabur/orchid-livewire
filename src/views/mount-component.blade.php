@isset($key)
    @livewire($name, $params, key($key))
@else
    @livewire($name, $params)
@endisset
