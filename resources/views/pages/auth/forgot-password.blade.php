@extends('layouts.guest')

@section('title', 'Forgot Password - E-Commerce Store')

@section('content')
<div class="card shadow">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <h2>Forgot Password</h2>
            <p class="text-muted">Enter your email address and we'll send you a link to reset your password.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('member.password.email') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    Send Password Reset Link
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
