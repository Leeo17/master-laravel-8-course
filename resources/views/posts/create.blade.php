@extends('layouts.app')

@section('title', 'Create the post')
@section('content')
  <form action="{{ route('posts.store') }}" method="POST">
    @csrf
    @include('posts.partials.form')
    <div class="d-grid gap-2 mt-3">
      <input type="submit" value="Create" class="btn btn-primary">
    </div>
  </form>
@endsection
