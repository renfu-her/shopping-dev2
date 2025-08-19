@extends('layouts.guest')

@section('title', 'Reset Password - E-Commerce Store')

@section('content')
<div class="card shadow">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <h2>Reset Password</h2>
            <p class="text-muted">Enter your new password below.</p>
        </div>

        <form method="POST" action="{{ route('member.password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ $email ?? old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" 
                       id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    Reset Password
                </button>
            </div>
        </form>

        <hr class="my-4">

        <div class="text-center">
            <p class="mb-0">
                <a href="{{ route('member.login') }}" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Login
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
