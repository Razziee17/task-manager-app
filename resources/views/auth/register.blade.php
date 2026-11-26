@extends('layouts.guest')

@section('title', 'Create account')

@section('content')
    <div class="auth-heading">
        <p class="page-eyebrow">Get started</p>
        <h1 class="auth-title">Create your account</h1>
        <p class="auth-description">Set up an account to start organizing your tasks.</p>
    </div>

    <form method="POST" action="{{ route('register.store') }}" class="auth-form">
        @csrf

        <label class="form-field">
            <span>Your name</span>
            <input type="text" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="255" required autofocus>
            @error('name')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </label>

        <label class="form-field">
            <span>Email address</span>
            <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" required>
            @error('email')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </label>

        <label class="form-field">
            <span>Password</span>
            <input type="password" name="password" autocomplete="new-password" minlength="8" required>
            <small class="form-hint">Use at least 8 characters.</small>
            @error('password')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </label>

        <label class="form-field">
            <span>Confirm password</span>
            <input type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required>
        </label>

        <button type="submit" class="primary-button auth-submit">Create account</button>
    </form>

    <p class="auth-switch">
        Already have an account?
        <a href="{{ route('login') }}">Log in</a>
    </p>
@endsection
