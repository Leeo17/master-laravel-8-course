<div class="mb-2 mt-2">
  @auth
    <form method="POST" action="{{ $route }}">
      @csrf
      <div class="form-group mb-3">
        <textarea class="form-control" id="content" name="content"></textarea>
      </div>
      <button type="submit" class="btn btn-primary btn-block">{{ __('Add comment') }}</button>
    </form>
    @errors @enderrors
  @else
    <a href="{{ route('login') }}">{{  __('Sign-in') }}</a> {{ __('to post comments!') }}
  @endauth
</div>
<hr />
