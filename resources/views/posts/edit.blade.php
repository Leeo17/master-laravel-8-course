@extends('layouts.app')

@section('title', 'Update the post')
@section('content')
  <form action="{{ route('posts.update', ['post' => $post->id]) }}" method="POST">
    @csrf
    @method('PUT')
    @include('posts.partials.form')
    <div class="d-grid gap-2 mt-3">
      <input type="submit" value="Create" class="btn btn-primary">
    </div>
  </form>
@endsection
