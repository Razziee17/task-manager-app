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

        <x-form-field label="Your name" name="name" autocomplete="name" maxlength="255" required autofocus />
        <x-form-field label="Email address" name="email" type="email" autocomplete="email" maxlength="255" required />
        <x-form-field label="Password" name="password" type="password" autocomplete="new-password" minlength="8" hint="Use at least 8 characters." required />
        <x-form-field label="Confirm password" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required />

        <button type="submit" class="primary-button auth-submit">Create account</button>
    </form>

    <p class="auth-switch">
        Already have an account?
        <a href="{{ route('login') }}">Log in</a>
    </p>
@endsection
