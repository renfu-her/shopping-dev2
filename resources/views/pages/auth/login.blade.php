@extends('layouts.guest')

@section('title', 'Login - E-Commerce Store')

@section('content')
<div class="card shadow">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <h2>Welcome Back</h2>
            <p class="text-muted">Sign in to your account to continue shopping</p>
        </div>

        <form method="POST" action="{{ route('member.login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    Sign In
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('member.password.request') }}" class="text-decoration-none">
                    Forgot your password?
                </a>
            </div>
        </form>

        <hr class="my-4">

        <div class="text-center">
            <p class="mb-0">Don't have an account? 
                <a href="{{ route('member.register') }}" class="text-decoration-none">Sign up</a>
            </p>
        </div>
    </div>
</div>
@endsection
