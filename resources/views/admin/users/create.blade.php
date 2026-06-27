@extends('layouts.admin')

@section('title', 'Create Admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Admin</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm mt-3 mt-sm-0">
            <i class="fas fa-list mr-1"></i> Admin Users
        </a>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">New admin account</h6>
                </div>

                <div class="card-body">
                    @include('admin.includes.flash_message')

                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" class="form-control" required>
                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Create Admin
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Admin access</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">New admins can open the admin panel, add products, edit categories, manage attributes, and review orders.</p>
                    <p class="mb-0 text-muted">Share the login email and password only with people you trust to manage the store.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
