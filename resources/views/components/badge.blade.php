@if (!isset($show) || $show)
  <span class="badge bg-{{ $type ?? 'primary' }}">{{ $slot }}</span>
@endif
