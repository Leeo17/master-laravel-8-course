@extends('layouts.app')
@section('content')
  <form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="form-group mb-3">
      <label>Name</label>
      <input name="name" value="{{ old('name') }}" required
        class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}">
      @if ($errors->has('name'))
        <span class="invalid-feedback">
          <strong>{{ $errors->first('name') }}</strong>
        </span>
      @endif
    </div>
    <div class="form-group mb-3">
      <label>Email</label>
      <input name="email" value="{{ old('email') }}" required
        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}">
      @if ($errors->has('email'))
        <span class="invalid-feedback">
          <strong>{{ $errors->first('email') }}</strong>
        </span>
      @endif
    </div>
    <div class="form-group mb-3">
      <label>Password</label>
      <input name="password" type="password" required
        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
      @if ($errors->has('password'))
        <span class="invalid-feedback">
          <strong>{{ $errors->first('password') }}</strong>
        </span>
      @endif
    </div>
    <div class="form-group mb-3">
      <label>Confirm Password</label>
      <input name="password_confirmation" type="password" required class="form-control">
    </div>
    <div class="d-grid gap-2 mt-3">
      <button type="submit" class="btn btn-primary">Register</button>
    </div>
  </form>
@endsection
