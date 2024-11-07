@if(request()->expectsJson())
    @json(['error' => 'Unauthorized', 'message' => 'Invalid Request'])
@else
    @extends('errors::minimal')

    @section('title', __('Unauthorized'))
    @section('code', '401')
    @section('message', __('Invalid Request'))
@endif
