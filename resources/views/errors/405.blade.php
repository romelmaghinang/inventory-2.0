@if(request()->expectsJson())
    @json(['error' => 'Method Not Allowed', 'message' => 'Unsupported Method'])
@else
    @extends('errors::minimal')

    @section('title', __('Method Not Allowed'))
    @section('code', '405')
    @section('message', __('Unsupported Method'))
@endif
