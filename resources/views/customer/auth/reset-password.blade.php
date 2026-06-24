@extends('layouts.frontend')

@section('title', 'Reset Password')

@section('content')
<section class="auth-page">
    <div class="auth-card">
        <h1>Create new password</h1>
        <form method="POST" action="{{ route('customer.password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <label>Email address</label>
            <input type="email" name="email" value="{{ old('email', $email) }}" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <label>Confirm password</label>
            <input type="password" name="password_confirmation" required>
            <button class="btn-primary full-width" type="submit">Reset password</button>
        </form>
    </div>
</section>
@endsection
