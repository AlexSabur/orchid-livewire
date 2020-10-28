@isset($key)
    @livewire($name, $params)    
@else
    @livewire($name, $params, key($key))    
@endif
