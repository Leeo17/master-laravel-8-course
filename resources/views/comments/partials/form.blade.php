<div class="mb-2 mt-2">
  @auth
    <form method="POST" action="{{ route('posts.comments.store', ['post' => $post->id]) }}">
      @csrf
      <div class="form-group mb-3">
        <textarea class="form-control" id="content" name="content"></textarea>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Add comment</button>
    </form>
    @errors @enderrors
  @else
    <a href="{{ route('login') }}">Sign in</a> to post comments!
  @endauth
</div>
<hr />
