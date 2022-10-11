@extends('layouts.app')

@section('title', __('Create the post'))
@section('content')
  <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('posts.partials.form')
    <div class="d-grid gap-2 mt-3">
      <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
    </div>
  </form>
@endsection
