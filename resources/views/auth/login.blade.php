@extends('layouts.guest')

@section('title', 'Log in')

@section('content')
    <div class="auth-heading">
        <p class="page-eyebrow">Welcome back</p>
        <h1 class="auth-title">Log in to your account</h1>
        <p class="auth-description">Enter your email and password to continue.</p>
    </div>

    @if ($errors->any())
        <div class="auth-alert" role="alert">
            Please check the email and password fields and try again.
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" class="auth-form">
        @csrf

        <label class="form-field">
            <span>Email address</span>
            <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
            @error('email')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </label>

        <label class="form-field">
            <span>Password</span>
            <input type="password" name="password" autocomplete="current-password" required>
            @error('password')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </label>

        <label class="remember-option">
            <input type="checkbox" name="remember" value="1">
            <span>Remember me</span>
        </label>

        <button type="submit" class="primary-button auth-submit">Log in</button>
    </form>

    <p class="auth-switch">
        New here?
        <a href="{{ route('register') }}">Create an account</a>
    </p>
@endsection
