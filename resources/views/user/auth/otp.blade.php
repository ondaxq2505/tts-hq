@extends('admin.auth.layouts.app')

@section('content')
    <div style="max-width: 400px; margin: 0 auto; padding: 40px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
        <h2 style="text-align: center; margin-bottom: 20px;"> Xác minh OTP</h2>

        @if (session('success'))
            <p style="color: green; text-align: center;">{{ session('success') }}</p>
        @endif

        @if (session('error'))
            <p style="color: red; text-align: center;">{{ session('error') }}</p>
        @endif

        <form method="POST" action="{{ route('user.otp.verify') }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label for="otp" style="display: block; font-weight: bold; margin-bottom: 8px;">Mã OTP:</label>
                <input type="text" name="otp" id="otp" required
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                @error('otp')
                    <span style="color: red; font-size: 13px;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit"
                    style="width: 100%; background-color: #007bff; color: #fff; border: none; padding: 10px 0; border-radius: 4px; cursor: pointer;">
                 Xác minh OTP
            </button>
        </form>

        <form method="POST" action="{{ route('user.otp.resend') }}" style="margin-top: 15px; text-align: center;">
            @csrf
            <input type="hidden" name="email" value="{{ session('otp_user_email') }}">
            <button type="submit"
                    style="background: none; border: none; color: #007bff; text-decoration: underline; cursor: pointer;"> Gửi lại mã OTP
            </button>
        </form>
    </div>
@endsection
