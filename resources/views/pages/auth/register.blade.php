@extends('layouts.guest')

@section('title', 'Register - E-Commerce Store')

@section('content')
<div class="card shadow">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <h2>Create Account</h2>
            <p class="text-muted">Join us and start shopping today</p>
        </div>

        <form method="POST" action="{{ route('member.register') }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                           id="phone" name="phone" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control @error('address') is-invalid @enderror" 
                          id="address" name="address" rows="3">{{ old('address') }}</textarea>
                @error('address')
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
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" 
                       id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror" 
                           id="terms" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and 
                        <a href="#" class="text-decoration-none">Privacy Policy</a>
                    </label>
                    @error('terms')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    Create Account
                </button>
            </div>
        </form>

        <hr class="my-4">

        <div class="text-center">
            <p class="mb-0">Already have an account? 
                <a href="{{ route('member.login') }}" class="text-decoration-none">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection
