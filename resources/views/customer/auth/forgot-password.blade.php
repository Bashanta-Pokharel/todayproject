@extends('layouts.frontend')

@section('title', 'Forgot Password')

@section('content')
<section class="auth-page">
    <div class="auth-card">
        <h1>Reset password</h1>
        <p>Enter your email and we will send a reset link.</p>
        <form method="POST" action="{{ route('customer.password.email') }}">
            @csrf
            <label>Email address</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            <button class="btn-primary full-width" type="submit">Send reset link</button>
        </form>
        <a href="{{ route('customer.login') }}">Back to login</a>
    </div>
</section>
@endsection
