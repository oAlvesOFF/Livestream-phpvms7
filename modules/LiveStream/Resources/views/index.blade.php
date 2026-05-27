@extends('livestream::layouts.frontend')

@section('title', 'LiveStream')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {{ config('livestream.name') }}
    </p>
@endsection
