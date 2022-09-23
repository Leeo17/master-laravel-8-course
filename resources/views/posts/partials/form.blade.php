<div class="form-group mb-3">
  <label for="title">Title</label>
  <input id="title" type="text" name="title" class="form-control"
    value="{{ old('title', optional($post ?? null)->title) }}">
</div>
<div class="form-group mb-3">
  <label for="content">Content</label>
  <textarea class="form-control" id="content" name="content">{{ old('content', optional($post ?? null)->content) }}</textarea>
</div>
<div class="form-group">
  <label>Thumbnail</label>
  <input type="file" name="thumbnail" class="form-control-file" />
</div>
@errors @enderrors
