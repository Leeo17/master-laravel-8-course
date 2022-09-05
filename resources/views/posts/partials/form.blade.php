<div class="form-group">
  <label for="title">Title</label>
  <input id="title" type="text" name="title" class="form-control"
    value="{{ old('title', optional($post ?? null)->title) }}">
</div>
@error('title')
  <div class="alert alert-danger mt-3">{{ $message }}</div>
@enderror
<div class="form-group">
  <label for="content">Content</label>
  <textarea class="form-control" id="content" name="content">{{ old('content', optional($post ?? null)->content) }}</textarea>
</div>
@if ($errors->any())
  <div class="mt-3 mb-3">
    <ul class="list-group">
      @foreach ($errors->all() as $error)
        <li class="list-group-item list-group-item-danger">{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
