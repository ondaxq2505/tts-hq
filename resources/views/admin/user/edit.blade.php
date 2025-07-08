@extends('admin._layouts.master')
@section('content')
    <h2>Chỉnh sửa User</h2>

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <label>Họ và tên:</label><br>
        <input type="text" name="fullname" value="{{ old('name', $user->fullname) }}"><br><br>

        <label>Username:</label><br>
        <input type="text" name="username" value="{{ old('username', $user->username) }}"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email', $user->email) }}"><br><br>

        <button type="submit">Cập nhật</button>
    </form>
@endsection
