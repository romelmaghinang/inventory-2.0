<!-- resources/views/errors/401.blade.php -->
@extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Invalid Request'))
