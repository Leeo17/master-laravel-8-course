@extends('layouts.app')

@section('title', 'Contact page')

@section('content')
  <h1>{{ __('Contact') }}</h1>
  @can('home.secret')
    <a href="{{ route('secret') }}">
      Go to special contact details
    </a>
  @endcan
@endsection
