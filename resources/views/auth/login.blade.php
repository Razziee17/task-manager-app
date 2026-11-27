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

        <x-form-field label="Email address" name="email" type="email" autocomplete="email" required autofocus />
        <x-form-field label="Password" name="password" type="password" autocomplete="current-password" required />

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
