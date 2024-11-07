@if(request()->expectsJson())
    @json(['error' => 'Method Not Allowed', 'message' => 'Unsupported Method'])
@else
    @extends('errors::minimal')

    @section('title', __('Unauthorized'))
    @section('code', '401')
    @section('message', __('Invalid Request'))
@endif
