@extends('layouts.app')

@section('title', $post->title)

@section('content')
  <h1>
    {{ $post->title }}
    @badge(['show' => now()->diffInMinutes($post->created_at) < 5])
      New Post!
    @endbadge
  </h1>
  <p>{{ $post->content }}</p>
  @updated(['date' => $post->created_at, 'name' => $post->user->name])
  @endupdated

  @updated(['date' => $post->updated_at])
    Updated
  @endupdated

  <p>Currently read by {{ $counter }} people</p>

  <h4>Comments</h1>
    @forelse ($post->comments as $comment)
      <p>
        {{ $comment->content }}
      </p>
      @updated(['date' => $comment->created_at])
      @endupdated
    @empty
      <p>No comments yet!</p>
    @endforelse
  @endsection
