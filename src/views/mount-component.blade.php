@if(is_null($key))
    @livewire($name, $params)    
@else
    @livewire($name, $params, key($key))    
@endif
