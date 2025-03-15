@extends('default_ui.header')

@section('title', 'Dashboard Page')

@push('styles')
    <style>
        .welcome-block {
            margin: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="welcome-block">
        <h2>Welcome to the Dashboard Page</h2>
        <p>This is the main content of the home page.</p>
    </div>
@endsection
