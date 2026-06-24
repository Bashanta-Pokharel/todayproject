@extends('layouts.frontend')

@section('title','Customer Register')

@section('content')

    <div style="
    min-height:80vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:40px 20px;
">

        <div style="
        width:100%;
        max-width:480px;
        background:#fff;
        border-radius:24px;
        padding:40px;
        box-shadow:0 10px 40px rgba(0,0,0,0.08);
        border:1px solid #f1f1f1;
    ">

            <!-- HEADER -->
            <div style="text-align:center;margin-bottom:30px;">

                <div style="
                font-size:32px;
                font-weight:700;
                margin-bottom:10px;
                color:#111;
            ">
                    Create Account
                </div>

                <div style="
                color:#777;
                font-size:14px;
            ">
                    Register to continue shopping
                </div>

            </div>

            <!-- ERRORS -->
            @if ($errors->any())

                <div style="
                background:#fff5f5;
                color:#e63946;
                padding:14px;
                border-radius:12px;
                margin-bottom:20px;
                font-size:14px;
            ">

                    <ul style="margin:0;padding-left:18px;">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif

            <!-- FORM -->
            <form method="POST" action="{{ route('customer.register') }}">

                @csrf

                <!-- NAME -->
                <div style="margin-bottom:18px;">

                    <label style="
                    display:block;
                    margin-bottom:8px;
                    font-size:14px;
                    font-weight:600;
                    color:#222;
                ">
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter your full name"
                        style="
                        width:100%;
                        height:52px;
                        border:1px solid #e5e5e5;
                        border-radius:14px;
                        padding:0 16px;
                        font-size:15px;
                        outline:none;
                    "
                    >

                </div>

                <!-- EMAIL -->
                <div style="margin-bottom:18px;">

                    <label style="
                    display:block;
                    margin-bottom:8px;
                    font-size:14px;
                    font-weight:600;
                    color:#222;
                ">
                        Email Address
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email"
                        style="
                        width:100%;
                        height:52px;
                        border:1px solid #e5e5e5;
                        border-radius:14px;
                        padding:0 16px;
                        font-size:15px;
                        outline:none;
                    "
                    >

                </div>

                <!-- PHONE -->
                <div style="margin-bottom:18px;">

                    <label style="
                    display:block;
                    margin-bottom:8px;
                    font-size:14px;
                    font-weight:600;
                    color:#222;
                ">
                        Phone Number
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="Enter your phone number"
                        style="
                        width:100%;
                        height:52px;
                        border:1px solid #e5e5e5;
                        border-radius:14px;
                        padding:0 16px;
                        font-size:15px;
                        outline:none;
                    "
                    >

                </div>

                <!-- PASSWORD -->
                <div style="margin-bottom:18px;">

                    <label style="
                    display:block;
                    margin-bottom:8px;
                    font-size:14px;
                    font-weight:600;
                    color:#222;
                ">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Enter password"
                        style="
                        width:100%;
                        height:52px;
                        border:1px solid #e5e5e5;
                        border-radius:14px;
                        padding:0 16px;
                        font-size:15px;
                        outline:none;
                    "
                    >

                </div>

                <!-- CONFIRM PASSWORD -->
                <div style="margin-bottom:25px;">

                    <label style="
                    display:block;
                    margin-bottom:8px;
                    font-size:14px;
                    font-weight:600;
                    color:#222;
                ">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="Confirm password"
                        style="
                        width:100%;
                        height:52px;
                        border:1px solid #e5e5e5;
                        border-radius:14px;
                        padding:0 16px;
                        font-size:15px;
                        outline:none;
                    "
                    >

                </div>

                <!-- BUTTON -->
                <button
                    type="submit"
                    style="
                    width:100%;
                    height:54px;
                    border:none;
                    border-radius:14px;
                    background:#111;
                    color:#fff;
                    font-size:15px;
                    font-weight:600;
                    cursor:pointer;
                    transition:0.3s;
                "
                >
                    Create Account
                </button>

            </form>

            <!-- LOGIN -->
            <div style="
            text-align:center;
            margin-top:24px;
            font-size:14px;
            color:#666;
        ">

                Already have an account?

                <a href="{{ route('customer.login') }}"
                   style="
                    color:#111;
                    font-weight:600;
                    text-decoration:none;
               ">
                    Login
                </a>

            </div>

        </div>

    </div>

@endsection
